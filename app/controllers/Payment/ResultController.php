<?php
namespace Controllers\Payment;

use Core\Controller;
use Models\Cart\Cart;
use Models\Product\ProductRepository;
use Models\Order\OrderRepository;
use Models\Order\OrderModel;
use App\Helpers\UrlHelper;

class ResultController extends Controller
{
    private Cart $cart;
    private OrderRepository $orderRepo;
    private ?array $paymentParams = null;
    private ?array $orderData = null;

    public function __construct()
    {
        $db = $this->loadDB();
        $model = new OrderModel($db);
        $this->orderRepo = new OrderRepository($model);
    }
    
    public function index(?string $status = null, ?string $paymentId = null, ?string $externalReference = null)
    {
        // Si vienen por GET, redirige con URL amigable
        if (isset($_GET['status'], $_GET['payment_id'], $_GET['external_reference'])) {
            $url = UrlHelper::base_url("payment/result/{$_GET['status']}/{$_GET['payment_id']}/{$_GET['external_reference']}");
            header("Location: $url");
            exit;
        }

        $this->paymentParams = [
            'status' => $status ?? 'unknown',
            'payment_id' => $paymentId,
            'external_reference' => $externalReference,
        ];

        // Consultar la orden si tenemos external_reference
        if ($externalReference) {
            $this->orderData = $this->loadOrderData((int)$externalReference);
        }

        // Decidir vista según status
        $this->renderByStatus($status);
    }

    private function loadOrderData(int $orderId): ?array
    {
        return $this->orderRepo->getOrderWithItems($orderId);
    }

    private function renderByStatus(string $status)
    {
        switch ($status) {
            case 'approved':
                $this->successView();
                break;
            case 'in_process': // pending - pendiente
                $this->pendingView();
                break;
            case 'failure':
            case 'rejected':
                $this->failureView();
                break;
            default:
                $this->failureView();
                break;
        }
    }

    private function successView()
    {
        $content = __DIR__ . '/../../view/payment/success.php';
        $title = '¡Gracias por tu compra! - Anime Shop';
        $assets = ['payment', 'cart'];

        $orderData = $this->orderData;
        $paymentId = $this->paymentParams['payment_id'];

        if ($orderData && $orderData['status'] === 'paid') {
            $db = $this->loadDB();
            $productRepo = new ProductRepository($db);

            // Disminuir stock según cada producto y cantidad
            foreach ($orderData['items'] as $item) {
                // Asegúrate de que los keys coincidan con tu resultado
                $productId = $item['product_id'] ?? null;
                $quantity = $item['quantity'] ?? 0;

                if ($productId && $quantity > 0) {
                    $productRepo->decreaseStock($productId, $quantity);
                }
            }

            $this->cart = new Cart($productRepo);
            $this->cart->clear();
        }

        include __DIR__ . '/../../view/layouts/base.php';
    }

    private function pendingView()
    {
        $content = __DIR__ . '/../../view/payment/pending.php';
        $title = 'Pago pendiente - Anime Shop';
        $assets = ['payment', 'cart'];

        $orderData = $this->orderData;
        $paymentId = $this->paymentParams['payment_id'];

        include __DIR__ . '/../../view/layouts/base.php';
    }

    private function failureView()
    {
        $content = __DIR__ . '/../../view/payment/failure.php';
        $title = 'Pago rechazado - Anime Shop';
        $assets = ['payment', 'cart'];

        $orderData = $this->orderData;
        $paymentId = $this->paymentParams['payment_id'];

        include __DIR__ . '/../../view/layouts/base.php';
    }
}
