<?php // url acceso: http://localhost/anime-shop/public/product/list ?>
<?php
/*
1. Navegación y catálogo
    - Visualizar productos por categoría (marcas, series, preventas, rebajas)
    - Filtrar productos por atributos (checkboxes) y ordenar resultados
    - Búsqueda por nombre del producto
        - Implementar la búsqueda básica (sin filtros, por nombre)
        - Después agregas los filtros como extras que se combinan con la búsqueda
    - Paginación en catálogo                                                            50%
        - configurar la paginación para mostrar 8 productos por página.
*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/assets/css/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../../public/assets/css/index.css">
    <title>Home</title>
</head>
<body>
    
    <!-- Seccion de catologo de productos -->
    <section class="section-catalog-product">
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
                <?php if ($product['is_on_sale']): ?>
                    <span class="badge badge-sale">¡Oferta!</span>
                <?php endif; ?>
                <?php if ($product['is_preorder']): ?>
                    <span class="badge badge-preorder">Preventa</span>
                <?php endif; ?>
            </div>
            <img src="../../public/assets/images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
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

                <?php if ($currentPage > 1): ?>
                    <li><a href="?page=<?= $currentPage - 1; ?>" class="page-previous">Anterior</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <li><a href="?page=<?= $i; ?>" class="page-current"><?= $i; ?></a></li>
                    <?php else: ?>
                        <li><a href="?page=<?= $i; ?>" class="page-number"><?= $i; ?></a></li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li><a href="?page=<?= $currentPage + 1; ?>" class="page-next">Siguiente</a></li>
                <?php endif; ?>
                <!-- <span class="points">...</span> --> <!-- pendiente por implementar logica-->
            </ul>
        </aside>
        <?php endif; ?>

    </section>

</body>
</html>