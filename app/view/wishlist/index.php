<?php
    use App\Helpers\SessionHelper;

    SessionHelper::start();
?>
<section class="wishlist-section">
    <?php if (isset($userId) && $userId): ?>
        <div class="wishlist-header">
            <h2>Mi Wishlist</h2>
            <p>Total de productos: <span id="total-products-wishlist-index"><?= SessionHelper::get('user_wishlist_count') ?? 0 ?></span></p>
        </div>

        <?php if (!empty($wishlistItems)): ?>
            <div class="wishlist-grid">
                <?php foreach ($wishlistItems as $item): ?>
                    <div class="wishlist-card">
                        <div class="wishlist-image">
                            <a href="/anime-shop/public/product/detail?product_id=<?= $item['product_id'] ?>&product_name=<?= $item['name'] ?>">
                                <img src="/anime-shop/public/assets/images/products/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            </a>
                        </div>
                        <h5 class="wishlist-name"><?= htmlspecialchars($item['name']) ?></h5>
                        <p class="wishlist-price">$<?= number_format($item['price'], 2) ?></p>
                        <button class="btn-add-to-cart" data-id="<?= $item['product_id'] ?>">
                            🛒 Agregar al carrito
                        </button>
                        <button 
                            class="btn-wishlist added" 
                            data-product-id="<?= $item['product_id'] ?>">
                            <i class="fa-solid fa-heart"></i> Eliminar
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="wishlist-empty">Tu wishlist está vacía 😢</p>
            <p>Explora nuestros productos y agrega tus favoritos a la wishlist.</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="wishlist-empty">
            Para visualizar productos en tu wishlist, por favor 
            <a href="/anime-shop/public/login" class="wishlist-login-link">inicia sesión</a>.
        </p>
    <?php endif; ?>
    <script async src="/anime-shop/public/assets/js/main.js" type="module"></script>
    <script async src=""></script>
</section>