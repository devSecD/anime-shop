<?php
use App\Helpers\SessionHelper;
use App\Helpers\FilterHelper;

$query = $_GET;
SessionHelper::start();
SessionHelper::regenerate();

$user = SessionHelper::getUser();
?>
<!-- Barra de navegacion -->
<nav class="navbar-main">
    <ul class="ul-main">
        <!-- Icono Hamburguesa -->
        <li class="li-menu-burger">
            <input type="checkbox" id="burger-toggle">
            <label for="burger-toggle" class="burger-container">
                <div class="burger-line"></div>
                <div class="burger-line"></div>
                <div class="burger-line"></div>
            </label>
        </li>

        <!-- Logo -->
        <li>
            <a href="/anime-shop/public/">
                <img class="logo" src="/anime-shop/public/assets/images/logo/Online_Store_Figures_Anime.webp" alt="logo">
            </a>
        </li>

        <!-- Buscador -->
        <li class="li-search">
            <form method="GET" action="/anime-shop/public/catalog">
                <input type="text" name="search" placeholder="search" class="input-search" value="<?=  isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';  ?>">
                <?php if (isset($_GET['filter'])): ?>
                    <input type="hidden" name="filter" value="<?= htmlspecialchars($_GET['filter']) ?>">
                <?php endif;?>
                <?php if (isset($_GET['sort'])): ?>
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']); ?>">
                <?php endif; ?>
                <?php if (isset($_GET['category'])): ?>
                    <input type="hidden" name="category" value="<?= htmlentities($_GET['category']); ?>">
                <?php endif; ?>
                <button type="submit" class="search-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </li>

        <li class="nav-icons-group">

            <!-- Usuario -->
            <div class="nav-user-dropdown">
                <a href="<?= SessionHelper::isLoggedIn() ? '#user' : '/anime-shop/public/user/login' ?>" class="nav-icon-with-text nav-user-info">
                    <i class="fa-regular fa-user"></i>
                    <span>
                        <?php if(SessionHelper::isLoggedIn()): ?>
                            <span class="user-greeting">¡ Bienvenido <?= $user['name'] ?> !</span><br>
                        <?php else: ?>
                            <span class="user-action">Identifícate / Regístrate</span>
                        <?php endif; ?>
                    </span>
                    <?php if(SessionHelper::isLoggedIn()): ?>
                        <i class="fa-solid fa-caret-down dropdown-arrow"></i>
                    <?php endif; ?>
                </a>
                <?php if(SessionHelper::isLoggedIn()): ?>
                    <ul class="dropdown-menu">
                        <?php if($user): ?>
                            <li><a href="/anime-shop/public/user/account">Mi cuenta</a></li>
                            <li><a href="/anime-shop/public/user/orders">Mis pedidos</a></li>
                            <li><a href="/anime-shop/public/user/logout">Cerrar sesión</a></li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Wishlist -->
            <div class="header-wishlist">
                <a href="/anime-shop/public/wishlist">
                    <i class="fa-solid fa-heart"></i>
                    <span id="wishlist-count"><?= SessionHelper::get('user_wishlist_count') ?? 0 ?></span>
                </a>
            </div>

            <!-- Carrito -->
            <div class="nav-cart-link">
                <a href="/anime-shop/public/cart" class="nav-icon-with-text nav-cart-link">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Carrito</span>
                    <span id="mini-cart-count" class="cart-badge"><?= $_SESSION['cart_count'] ?? 0; ?></span>
                </a>
            </div>
        </li>

    </ul>
</nav>

<!-- Menu hamburguesa móvil -->
<?php if(isset($pages) && (in_array('home', $pages) || in_array('catalog', $pages))): ?>
    <?php include __DIR__ . '/_navbar_secondary.php' ?>
<?php endif; ?>