<?php
namespace Controllers\Checkout;

use Core\Controller;
use Models\Cart\Cart;
use Models\Product\ProductRepository;
use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\SessionHelper;

class AddressController extends Controller
{
    private Cart $cart;

    public function __construct()
    {
        $db = $this->loadDB();
        $repo = new ProductRepository($db);
        $this->cart = new Cart($repo);
    }

    // GET /checkout/address
    public function index(): void
    {
        $items = $this->cart->items();
        if (empty($items)) {
            header("Location: /anime-shop/public/cart");
            exit;
        }

        $content = __DIR__ . '/../../view/checkout/address.php';
        $title = 'Dirección de envío';
        $page = 'shipping_address';
        $assets = ['checkout', 'cart', 'form'];

        $db = $this->loadDB();
        $repo = new \Models\Checkout\ShippingAddressRepository($db);

        // Obtiene el usuario
        $user = SessionHelper::getUser();
        $userId = $user['user_id'] ?? null;

        $shippingAddresses = $repo->getAllByUser($userId);

        include __DIR__ . '/../../view/layouts/base.php';
    }

    public function createShippingAddressForm(): void
    {
        ValidationHelper::rejectIfNotPost();

        $data = [
            'fullname' => $_POST['fullname'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'email' => $_POST['email'] ?? null,
            'street' => $_POST['street'] ?? null,
            'neighborhood' => $_POST['neighborhood'] ?? null,
            'postal_code' => $_POST['postal_code'] ?? null,
            'city' => $_POST['city'] ?? null,
            'state' => $_POST['state'] ?? null,
            'country' => $_POST['country'] ?? null,
            'notes' => $_POST['notes'] ?? null,
        ];

        $errors = [];

        // Validar campos obligatorios y con reglas
        if ($error = ValidationHelper::required('nombre completo', $data['fullname']))
            $errors['fullname'] = $error;
        else if ($error = ValidationHelper::validateName($data['fullname']))
            $errors['fullname'] = $error;

        if ($error = ValidationHelper::required('teléfono', $data['phone']))
            $errors['phone'] = $error;
        else if ($error = ValidationHelper::validatePhone($data['phone']))
            $errors['phone'] = $error;

        if ($error = ValidationHelper::required('correo electrónico', $data['email']))
            $errors['email'] = $error;
        else if ($error = ValidationHelper::validateEmail($data['email']))
            $errors['email'] = $error;

        if ($error = ValidationHelper::required('calle y número', $data['street']))
            $errors['street'] = $error;

        if ($error = ValidationHelper::required('colonia', $data['neighborhood']))
            $errors['neighborhood'] = $error;

        if ($error = ValidationHelper::required('código postal', $data['postal_code']))
            $errors['postal_code'] = $error;
        else if ($error = ValidationHelper::validatePostalCode($data['postal_code']))
            $errors['postal_code'] = $error;

        if ($error = ValidationHelper::required('ciudad', $data['city']))
            $errors['city'] = $error;

        if ($error = ValidationHelper::required('estado', $data['state']))
            $errors['state'] = $error;

        if ($error = ValidationHelper::required('país', $data['country']))
            $errors['country'] = $error;

        // Si hay errores, responder
        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors
            ]);
        }

        $db = $this->loadDB();
        $repo = new \Models\Checkout\ShippingAddressRepository($db);

        // Verifica si el usuario está logueado
        if (!SessionHelper::isLoggedIn()) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => 'Debes iniciar sesión para continuar.'
            ]);
        }

        // Obtiene el usuario
        $user = SessionHelper::getUser();
        $userId = $user['user_id'] ?? null;

        $addressData = $data + ['user_id' => $userId];

        $result = $repo->saveAdress($addressData);

        if ($result['success']) {
            ResponseHelper::jsonResponse([
                'success' => true, 
                'message' => 'Dirección  guardada. Redirigiendo a pago...', 
                'redirect' => '/anime-shop/public/checkout/payment'
            ]);
        } else {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => $result['message']
            ]);
        }

    }

    public function confirmShippingAddress(): void
    {

        $addressId = $_POST['shipping_address_id'] ?? null;

        $user = SessionHelper::getUser();
        $userId = $user['user_id'] ?? null;

        if ($error = ValidationHelper::required('método de pago', $addressId)) {
            $errors['address_id'] = $error;
        }

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => StringHelper::implodeArray($errors, "\n"), 
                'errors' => $errors
            ]);
        }

        $db = $this->loadDB();
        $addressRepository = new \Models\Checkout\ShippingAddressRepository($db);

        $isValid = $addressRepository->validateAddressBelongsToUser((int)$addressId, (int)$userId);

        if (!$isValid) {
            ResponseHelper::jsonResponse(['success' => false, 'message' => 'Dirección inválida o no pertenece al usuario.']);
            return;
        }

        ResponseHelper::jsonResponse([
            'success' => true, 
            'message' => 'Dirección seleccionada. Redirigiendo a pago...', 
            'redirect' => '/anime-shop/public/checkout/payment'
        ]);

    }

}