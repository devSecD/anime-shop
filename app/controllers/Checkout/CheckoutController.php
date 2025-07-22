<?php
namespace Controllers\Checkout;

use Core\Controller;
use Models\Cart\Cart;
use Models\Product\ProductRepository;

class CheckoutController extends Controller
{
    private Cart $cart;

    public function __construct()
    {
        $db = $this->loadDB();
        $repo = new ProductRepository($db);
        $this->cart = new Cart($repo);
    }

    // Get /checkout
    public function index(): void
    {
        $items = $this->cart->items();
        $total = $this->cart->total();

        if (empty($items)) {
            header("Location: /anime-shop/public/cart");
            exit;
        }

        $content = __DIR__ . '/../../view/checkout/index.php';
        $title = 'Checkout - Resumen del pedido';
        $page = 'checkout';
        $assets = ['checkout', 'cart'];
        $cartData = compact('items', 'total');

        include __DIR__ . '/../../view/layouts/base.php';
    }
}