<?php
namespace Controllers\Cart;

use Core\Controller;
use Models\Product\ProductRepository;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use Models\Cart\Cart;

class CartController extends Controller
{
    private Cart $cart;
    private ProductRepository $repo;

    public function __construct()
    {
        $db    = $this->loadDB();
        $this->repo  = new ProductRepository($db);
        $this->cart = new Cart($this->repo);
    }

    public function add(): void
    {
        $id = (int)($_POST['product_id'] ?? 0);
        $qty = (int)($_POST['qty'] ?? 1);

        // 💡 Obtener cantidad actual en el carrito para este producto
        $currentQty = $this->cart->getQuantity($id);
        $totalQty = $currentQty + $qty;

        // Validar stock anttes de agregar
        $error = ValidationHelper::validateStock($this->repo, $id, $totalQty);
        if ($error) {
            http_response_code(400);
            ResponseHelper::jsonResponse([
                'success' => false, 
                'error_type' => 'validation',
                'message' => 'No puedes agregar mas del mismo producto'
            ]);
        }

        try {
            $this->cart->add($id, $qty);
            ResponseHelper::jsonResponse([
                'success' => true, 
                'count' => $this->cart->count(),
                'total' => $this->cart->total(), 
                'message' => 'Producto añadido al carrito'
            ]);
        } catch (\Throwable $e) {
            http_response_code(400);
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(): void
    {
        $id = (int)($_POST['product_id'] ?? 0);
        $qty = (int)($_POST['qty'] ?? 1);

        // Validar stock antes de actualizar
        $error = ValidationHelper::validateStock($this->repo, $id, $qty);
        if ($error) {
            http_response_code(400);
            ResponseHelper::jsonResponse([
                'success' => false, 
                'error_type' => 'validation',
                'message' => $error
            ]);
        }   

        try {
            $this->cart->update($id, $qty);
            ResponseHelper::jsonResponse([
                'success' => true, 
                'count' => $this->cart->count(), 
                'total' => $this->cart->total(), 
                'message' => 'Cantidad actualizada'
            ]);
        } catch (\Throwable $e) {
            http_response_code(400);
            ResponseHelper::jsonResponse([
                'success' => false, 
                'error_type' => 'exception',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function remove(): void
    {
        $id = (int)($_POST['product_id'] ?? 0);

        try {
            $this->cart->remove($id);
            ResponseHelper::jsonResponse([
                'success' => true, 
                'count' => $this->cart->count(), 
                'total' => $this->cart->total(), 
                'message' => 'Producto eliminado del carrito'
            ]);
        } catch (\Throwable $e) {
            http_response_code(400);
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }

    public function count()
    {
        header('Content-Type: application/json'); // esta de mas porque con jsonResponse ya lo seteamos
        ResponseHelper::jsonResponse([
            'count' => $this->cart->count(),
        ]);
    }

    public function view(): void
    {
        $items = $this->cart->items();
        $total = $this->cart->total();

        $content = __DIR__ . '/../../view/cart/index.php';
        $title = 'Tu carrito - Anime Shop';
        $page = 'cart';
        $assets = ['cart'];

        $cartData = compact('items', 'total');

        include __DIR__ . '/../../view/layouts/base.php';
    }
}