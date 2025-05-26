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
    <h2>Catálogo completo</h2>
    <?php include __DIR__ . '/../products/_catalog_partial.php'; ?>
</body>
</html>