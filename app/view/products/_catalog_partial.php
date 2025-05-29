<section class="section-catalog-product" style="margin-top: 9rem;">
    <!-- Coleccion de barra de herramientas -->
    <!--
    <aside class="aside-collection-toolbar">
        <aside class="aside-filter">
            <input type="checkbox" id="filter-toggle">
            <label for="filter-toggle" class="filter-menu-container" onclick="document.getElementById('nav-filter').style.display = 'block'; document.getElementById('close_search').style.display = 'block';">
                <i class="fa-solid fa-filter"></i>
                Filtro
            </label>
            <div class="close_search" id="close_search" onclick="document.getElementById('nav-filter').style.display = 'none'; this.style.display = 'none';"><a>x</a></div>
            <nav class="nav-filter" id="nav-filter">
                <ul>
                    <li><a href="#">Filtro 1</a></li>
                    <li><a href="#">Filtro 2</a></li>
                    <li><a href="#">Filtro 3</a></li>
                </ul>
            </nav>
        </aside>
    </aside>
    -->

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
        <img src="../public/assets/images/products/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
        <h5><?= htmlspecialchars($product['name']); ?></h5>
        <p><?= number_format($product['price'], 2); ?></p>
        <form action="">
            <input type="button" value="Añadir al carrito">
        </form>
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