<?php
namespace Controllers\Checkout;

use Core\Controller;

use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\SessionHelper;
use Models\Order\OrderItemModel;
use Models\Order\OrderModel;
use Models\Order\OrderRepository;
use Models\Product\ProductRepository;
use Models\Checkout\ShippingAddressRepository;
use App\Services\MercadoPagoService;
use Models\Cart\Cart;

require_once __DIR__ . '/../../../vendor/autoload.php';
use Models\Order\OrderItemRepository;

class PaymentController extends Controller
{
    private Cart $cart;

    public function __construct()
    {
        $db = $this->loadDB();
        $repo = new ProductRepository($db);
        $this->cart = new Cart($repo);
    }

    // tenia como parametro el metodo index: int $status
    public function index(): void
    {

        SessionHelper::start();

        $items = $this->cart->items();
        $total = $this->cart->total();

        if (empty($items)) {
            header("Location: /anime-shop/public/cart");
            exit;
        }

        $user = SessionHelper::getUser();
        if (!$user) {
            header("Location: /anime-shop/public/login");
            exit;
        }

        $userId = $user['user_id'];

        // Direcciones de envío
        $db = $this->loadDB();
        $addressRepo = new ShippingAddressRepository($db);
        $shippingAddresses = $addressRepo->getAllByUser($userId);

        $content = __DIR__ . '/../../view/checkout/payment.php';
        $title = 'Paso de Pago';
        $page = 'checkout_payment';
        $assets = ['checkout', 'cart', 'form'];

        extract(compact('items', 'total', 'shippingAddresses'));

        include __DIR__ . '/../../view/layouts/base.php';
    }

    public function process()
    {
        ValidationHelper::rejectIfNotPost();

        if (!SessionHelper::isLoggedIn()) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => 'Debes iniciar sesión para continuar con el pago.'
            ]);
        }

        $db = $this->loadDB();
        $addressRepo = new ShippingAddressRepository($db);

        $data = [
            'shipping_address_id' => $_POST['shipping_address_id'] ?? null,
            'total' => $_POST['total'] ?? null
        ];

        $errors = [];

        if ($error = ValidationHelper::required('dirección de envío', $data['shipping_address_id']))
            $errors['shipping_address_id'] = $error;
        else if ($error = ValidationHelper::validateShippingAddress(
            fn($id) => $addressRepo->findById($id),
            (int)$data['shipping_address_id']
        )) $errors['shipping_address_id'] = $error;

        $total = $this->cart->total();
        if ($error = ValidationHelper::validatePositive((float)$total))
            $errors['total'] = $error;
        
        $items = $this->cart->items();

        if (empty($items)) {
            $errors['products'] = 'No hay productos en el carrito.';
        } else {
        $productRepo = $this->cart->getProductRepository();
            foreach ($items as $productId => $item) {
                $quantity = $item['qty'] ?? null;

                if (!$productId || !$quantity) {
                    $errors["products[$productId]"] = "Falta id o cantidad del producto.";
                    continue;
                }

                if ($error = ValidationHelper::validateStock($productRepo, (int)$productId, (int)$quantity)) {
                    $errors["products[$productId]"] = $error;
                }
            }
        }

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => StringHelper::implodeArray($errors, "\n"), 
                'errors' => $errors
            ]);
        }

        $orderModel = new OrderModel($db);
        $orderRepo = new OrderRepository($orderModel);

        $orderItemModel = new OrderItemModel($db);
        $orderItemRepo = new OrderItemRepository($orderItemModel);

        $user = SessionHelper::getUser();
        $userId = $user['user_id'] ?? null;

        $shippingAddressId = (int)$data['shipping_address_id'];

        $orderId = $orderRepo->createOrder(
            $userId, 
            (float)$total, 
            null, 
            $shippingAddressId, 
            'pending'
        );

         // Crear preferencia
        $mpService = new MercadoPagoService();
        $preference = $mpService->createPreference($items, $orderId);

        // ✅ Actualizamos en la BD el preference_id que nos dio Mercado Pago
        $orderRepo->updatePreferenceId($orderId, $preference->id);

        // Guardamos items
        foreach ($items as $productId => &$item) {
            $item['product_id'] = $productId;
        }

        $orderItemRepo->createItems($orderId, $items);

        // ✅ Responder con JSON para que JS redirija
        ResponseHelper::jsonResponse([
            'success' => true,
            'message' => 'Redirigiendo a Mercado Pago...',
            'redirect' => $preference->init_point
        ]);

    }
}