<!-- Filtros superiores -->
<?php

use App\Helpers\FilterHelper;
use App\Helpers\RequestHelper;

/*
app\view\components\_navbar_secondary.php
Las funciones:
* isActiveFilter
* isAllActive

Se deben meter en otro lado. Mi propuesta es que deben ser helpers en esta ruta estan app\helpers.
Auque tambien tengo en esta ruta app\helpers\ValidationHelper.php helpers
¿En cual de los dos deberia de meterlo?

SE METIO EN LA RUTA app\helpers Y SE CREO EL ARCHIVO LLAMADO FilterHelper
TAMBIEN SE TUVO QUE MODIFICAR EL CODIGO DEL AUTOLOAD DEL METODO autoload para que agarrara el namespace de los filteR, 
YA QUE ESTABA LEYENDO SOLO DEDSDE CORE
*/


$query = $_GET;

?>
<nav class="navbar-secondary">
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
<!-- Filtros superiores -->