<?php
namespace Models\Cart;

use App\Helpers\SessionHelper;
use Models\Product\ProductRepository;

class Cart
{
    private const SESSION_KEY = 'cart';

    protected ProductRepository $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
        $this->init();
    }

    public function getProductRepository(): ProductRepository
    {
        return $this->repo;
    }

    // Inicializa el carrito si no existe en sesión
    public function init(): void
    {
        SessionHelper::start();
        if (!SessionHelper::get(self::SESSION_KEY)) {
            SessionHelper::set(self::SESSION_KEY, []); // [product_id => ['qty'=>n, 'price'=>x, ...]]
        }
    }

    public function add(int $productId, int $qty = 1): void
    {
        $product = $this->repo->findById($productId);

        if (!$product) throw new \RuntimeException('Producto no encontrado');

        if ($product['stock'] < 1) throw new \RuntimeException('Sin stock disponible');

        $cart = SessionHelper::get(self::SESSION_KEY);

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $qty;
        } else {
            $cart[$productId] = [
                'qty' => $qty, 
                'price' => $product['price_discounted'] ?: $product['price'],
                'name' => $product['name'], 
                'image' => $product['image']
            ];
        }

        SessionHelper::set(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $qty): void
    {
        if ($qty <= 0) {
            $this->remove($productId);
            return;
        }

        $cart = SessionHelper::get(self::SESSION_KEY);
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] = $qty;
            SessionHelper::set(self::SESSION_KEY, $cart);
        }
    }

    public function remove(int $productId): void
    {
        $cart = SessionHelper::get(self::SESSION_KEY);
        unset($cart[$productId]);
        SessionHelper::set(self::SESSION_KEY, $cart);
    }

    // retorna arreglo asociativo
    public function items(): array
    {
        return SessionHelper::get(self::SESSION_KEY) ?? [];
    }

    public function getQuantity(int $productId): int
    {
        $items = $this->items();
        return isset($items[$productId]) ? (int)$items[$productId]['qty'] : 0;
    }

    public function total(): float
    {
        // Recorre todos los items del carrito y retorna la suma total de (precio * cantidad) iniciando en 0.0
        return array_reduce($this->items(), fn($t, $i) => $t + $i['price'] * $i['qty'], 0.0);
    }

    public function count(): int
    {
        // Recorre todos los items del carrito y retorna la suma total de las cantidades (qty) iniciando en 0
        return array_reduce($this->items(), fn($c, $i) => $c + $i['qty'], 0);
    }

     // Vacía todo el carrito de la sesión.
    public function clear(): void
    {
        SessionHelper::start();
        SessionHelper::set(self::SESSION_KEY, []);
    }

}