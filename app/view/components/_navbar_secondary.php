<!-- Filtros superiores -->
<?php
// Para los filters top o filtros superiores
$currentFilter = $_GET['filter'] ?? null;
$currentSort = $_GET['sort'] ?? null;
$currentCategory = $_GET['category'] ?? null;

function isActiveFilter($type, $value) {
    return (isset($_GET[$type]) && $_GET[$type] === $value) ? 'active-filter' : '';
}

function isAllActive() {
    $filter = $_GET['filter'] ?? null;
    $sort = $_GET['sort'] ?? null;
    $category = $_GET['category'] ?? null;

    $hasRealFilters = !empty($filter) || !empty($sort) || !empty($category);
    return !$hasRealFilters ? 'active-filter' : '';
}
?>
<nav class="navbar-secondary">
    <ul class="ul-secondary">
        <li>
            <a class="<?= isAllActive() ?>" href="../public/catalog">Todos</a>
        </li>
        <li>
            <a class="<?= isActiveFilter('filter', 'in-stock') ?>" href="../public/catalog?filter=in-stock">Disponibles</a>
        </li>
        <li>
            <a class="<?= isActiveFilter('filter', 'discounted') ?>" href="../public/catalog?filter=discounted">En oferta</a>
        </li>
        <li>
            <a class="<?= isActiveFilter('sort', 'newest') ?>" href="../public/catalog?sort=newest">Novedades</a>
        </li>
        <li>
            <a class="<?= isActiveFilter('sort', 'top-sellers') ?>" href="../public/catalog?sort=top-sellers">Mas vendidos</a>
        </li>
    </ul>
</nav>
<!-- Filtros superiores -->