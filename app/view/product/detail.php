<?php use App\Helpers\StringHelper; ?>
<!-- app/view/product/detail.php -->
<section class="product-detail-container">
    <!-- Galería de imágenes + Información -->
    <div class="product-gallery">
        <!-- Imagen principal y miniaturas -->
        <div class="product-images">
            <img id="main-image" 
                 src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
                 alt="<?= htmlspecialchars($product['name']) ?>">

            <!-- Modal de imagen -->
            <div id="imageModal">
                <span id="closeModal">&times;</span>
                <img id="modalImage">
            </div>

            <?php if (!empty($product['images'])): ?>
                <div class="thumbnail-list">
                    <?php foreach ($product['images'] as $img): ?>
                        <img src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($img) ?>" 
                             alt="Miniatura <?= htmlspecialchars($product['name']) ?>" 
                             onclick="document.getElementById('main-image').src = this.src">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <!-- Información del producto -->
        <div class="product-info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p class="price">$<?= number_format($product['price'], 2) ?></p>
            <span class="stock"><?= $product['stock'] > 0 ? 'En stock' : 'Agotado' ?></span>
            <button class="btn-add-to-cart" data-id="<?= $product['product_id'] ?>">🛒 Agregar al carrito</button>
            <button class="btn-wishlist" data-id="<?= $product['product_id'] ?>">
                <i class="fa-solid fa-heart"></i>    
                Agregar a Wishlist
            </button>
            <div class="product-description">
                <h3>Descripción</h3>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Productos relacionados: sección aparte a todo ancho -->
<section class="related-products">
    <h3>Productos relacionados</h3>
    <?php if (!empty($relatedProducts)): ?>
        <div class="related-products-grid">
            <?php foreach ($relatedProducts as $rp): ?>
                <div class="container-product">
                    <a href="/anime-shop/public/product/detail?product_id=<?= $rp['product_id'] ?>&product_name=<?= StringHelper::generateSlug($rp['name']) ?>">
                        <img src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($rp['image']) ?>" 
                            alt="<?= htmlspecialchars($rp['name']) ?>">
                    </a>
                    <h5><?= htmlspecialchars($rp['name']) ?></h5>
                    <p class="price">$<?= number_format($rp['price'], 2) ?></p>
                    <button data-id="<?= $rp['product_id'] ?>" class="btn-add-to-cart">Agregar al carrito</button>
                    <button data-id="<?= $rp['product_id'] ?>" class="btn-wishlist">
                        <i class="fa-regular fa-heart"></i>
                        Wishlist
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-related">No hay productos relacionados disponibles.</p>
    <?php endif; ?>
</section>

<!-- Reseñas de usuarios -->
<section class="product-reviews">
    <h3>Opiniones de usuarios</h3>

    <?php if (!empty($product['reviews'])): ?>
        <ul>
            <?php foreach ($product['reviews'] as $review): ?>
                <li>
                    <div class="review-header">
                        <strong><?= htmlspecialchars($review['user_name']) ?></strong>
                        <span><?= date('d M Y', strtotime($review['created_at'])) ?></span>
                    </div>
                    <div class="review-stars">
                        <?php 
                            $fullStars = floor($review['rating']);
                            $emptyStars = 5 - $fullStars;
                            for($i=0; $i < $fullStars; $i++) echo '★';
                            for($i=0; $i < $emptyStars; $i++) echo '☆';
                        ?>
                    </div>
                    <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="no-reviews">Aún no hay opiniones de usuarios.</p>
    <?php endif; ?>
</section>

<script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
<?php if (in_array('cart', $assets)): ?>
    <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
<?php endif; ?>