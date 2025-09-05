<!-- Filtros superiores -->
<?php

use App\Helpers\FilterHelper;
use App\Helpers\RequestHelper;

$query = $_GET;

?>
<nav class="navbar-secondary desktop-only">
    <ul class="ul-secondary">
        <li>
            <a class="<?= FilterHelper::isAllActive($query, ['filter', 'sort', 'category']) ? 'active-filter' : '' ?>" href="../public/catalog">Todos</a>
        </li>
        <li>
            <a class="<?= FilterHelper::isActiveFilter($query, 'filter', 'in-stock') ? 'active-filter' : '' ?>" href="../public/catalog?filter=in-stock">Disponibles</a>
        </li>
        <li>
            <a class="<?= FilterHelper::isActiveFilter($query, 'filter', 'discounted') ? 'active-filter' : '' ?>" href="../public/catalog?filter=discounted">En oferta</a>
        </li>
        <li>
            <a class="<?= FilterHelper::isActiveFilter($query, 'sort', 'newest') ? 'active-filter' : '' ?>" href="../public/catalog?sort=newest">Novedades</a>
        </li>
        <li>
            <a class="<?= FilterHelper::isActiveFilter($query, 'sort', 'top-sellers') ? 'active-filter' : '' ?>" href="../public/catalog?sort=top-sellers">Mas vendidos</a>
        </li>
    </ul>
</nav>

<nav class="navbar-secondary menu-burger mobile-only">
    <ul class="ul-secondary">
        <li>
            <a class="<?= FilterHelper::isAllActive($query, ['filter', 'sort', 'category']) ? 'active-filter' : '' ?>" href="../public/catalog">Todos</a>
        </li>
        <li>
            <a class="<?= FilterHelper::isActiveFilter($query, 'filter', 'in-stock') ? 'active-filter' : '' ?>" href="../public/catalog?filter=in-stock">Disponibles</a>
        </li>
        <li>
            <a class="<?= FilterHelper::isActiveFilter($query, 'filter', 'discounted') ? 'active-filter' : '' ?>" href="../public/catalog?filter=discounted">En oferta</a>
        </li>
        <li>
            <a class="<?= FilterHelper::isActiveFilter($query, 'sort', 'newest') ? 'active-filter' : '' ?>" href="../public/catalog?sort=newest">Novedades</a>
        </li>
        <li>
            <a class="<?= FilterHelper::isActiveFilter($query, 'sort', 'top-sellers') ? 'active-filter' : '' ?>" href="../public/catalog?sort=top-sellers">Mas vendidos</a>
        </li>
    </ul>
</nav>