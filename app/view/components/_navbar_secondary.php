<!-- Filtros superiores -->
<?php

$query = $_GET;

// Para los filters top o filtros superiores
$currentFilter = $_GET['filter'] ?? null;
$currentSort = $_GET['sort'] ?? null;
$currentCategory = $_GET['category'] ?? null;

    function isActiveFilter(array $queryParams, $type, $value) {
    return (isset($queryParams[$type]) && $queryParams[$type] === $value) ? 'active-filter' : '';
}

    function isAllActive(array $queryParams) {
    $filter = $queryParams['filter'] ?? null;
    $sort = $queryParams['sort'] ?? null;
    $category = $queryParams['category'] ?? null;

    $hasRealFilters = !empty($filter) || !empty($sort) || !empty($category);
    return !$hasRealFilters ? 'active-filter' : '';
}
?>
<nav class="navbar-secondary">
    <ul class="ul-secondary">
        <li>
            <a class="<?= isAllActive($query) ?>" href="../public/catalog">Todos</a>
        </li>
        <li>
            <a class="<?= isActiveFilter($query, 'filter', 'in-stock') ?>" href="../public/catalog?filter=in-stock">Disponibles</a>
        </li>
        <li>
            <a class="<?= isActiveFilter($query, 'filter', 'discounted') ?>" href="../public/catalog?filter=discounted">En oferta</a>
        </li>
        <li>
            <a class="<?= isActiveFilter($query, 'sort', 'newest') ?>" href="../public/catalog?sort=newest">Novedades</a>
        </li>
        <li>
            <a class="<?= isActiveFilter($query, 'sort', 'top-sellers') ?>" href="../public/catalog?sort=top-sellers">Mas vendidos</a>
        </li>
    </ul>
</nav>
<!-- Filtros superiores -->