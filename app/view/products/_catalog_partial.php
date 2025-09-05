<?php use App\Helpers\StringHelper; ?>
<section class="section-catalog-product">
    <!-- Primera fila -->
    <?php foreach($products as $product): ?>
    <section class="container-product">
        <div class="badge-container">
            <?php if ($product['is_on_sale'] && $product['price_discounted'] > 0): ?>
                <span class="badge badge-sale">¡Oferta!</span>
            <?php endif; ?>
            <?php if ($product['is_preorder']): ?>
                <span class="badge badge-preorder">Preventa</span>
            <?php endif; ?>
        </div>
        <!-- Enlace a detalle del producto -->
        <a href="/anime-shop/public/product/detail?product_id=<?= $product['product_id'] ?>&product_name=<?= StringHelper::generateSlug($product['name']) ?>">
            <img src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
        </a>
        <!-- Título clickeable -->
        <h5>
            <a href="/anime-shop/public/product/detail/?product_id=<?= $product['product_id'] ?>&product_name=<?= StringHelper::generateSlug($product['name']) ?>">
                <?= htmlspecialchars($product['name']); ?>
            </a>
        </h5>
        <p><?= number_format($product['price'], 2); ?></p>
        <!-- <input type="button" value="Añadir al carrito"> -->
            <button class="btn-add-to-cart"
                data-id="<?= $product['product_id'] ?>"
                data-qty="1">
            Añadir al carrito
        </button>
    </section>
    <?php endforeach; ?>

    <?php if ($totalPages > 1): ?>
    <!-- Seccion de paginado -->
    <aside class="aside-paginated">
        <ul class="page-list">

            <?php
            // Paramatros persistentes
            $filter = urlencode($_GET['filter'] ?? '');
            $sort = urlencode($_GET['sort'] ?? '');
            $category = urlencode($_GET['category'] ?? '');
            $search = urlencode($_GET['search'] ?? '');

            $extraParams = "&search=$search&filter=$filter&sort=$sort&category=$category";
            ?>

            <?php if ($currentPage > 1): ?>
                <li><a href="?page=<?= $currentPage - 1 . $extraParams; ?>" class="page-previous">Anterior</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                    <li><a href="?page=<?= $i . $extraParams; ?>" class="page-current"><?= $i; ?></a></li>
                <?php else: ?>
                    <li><a href="?page=<?= $i . $extraParams; ?>" class="page-number"><?= $i; ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <li><a href="?page=<?= $currentPage + 1 . $extraParams; ?>" class="page-next">Siguiente</a></li>
            <?php endif; ?>
            <!-- <span class="points">...</span> --> <!-- pendiente por implementar logica-->
        </ul>
    </aside>
    <?php endif; ?>

</section>

<?php if (in_array('cart', $assets)): ?>
    <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
<?php endif; ?>