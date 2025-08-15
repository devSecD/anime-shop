<!-- Barra de navegacion -->
<nav class="navbar-main">
    <ul class="ul-main">
        <li class="li-menu-burger">
            <!-- Menu de hamburguesa -->
            <!-- Hidden Checkbox to Control Menu -->
            <input type="checkbox" id="burger-toggle">
            <!-- Burger Icon -->
            <label for="burger-toggle" class="burger-container">
                <div class="burger-line"></div>
                <div class="burger-line"></div>
                <div class="burger-line"></div>
            </label>
            <!-- Menu de hamburguesa -->
            <nav class="menu-burger">
                <ul class="">
                    <li>
                        <a href="#inicio">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="#marcas">
                            Marcas
                        </a>
                    </li>
                    <li>
                        <a href="#series">
                            Series
                        </a>
                    </li>
                    <li>
                        <a href="#preventas">
                            Preventas
                        </a>
                    </li>
                    <li>
                        <a href="#rebajas">
                            Rebajas
                        </a>
                    </li>
                </ul>
            </nav>
        </li>
        <li>
            <a href="#logo">
                <img class="logo" src="/anime-shop/public/assets/images/logo/Online_Store_Figures_Anime.webp" alt="logo">
            </a>
        </li>
        <li class="li-search">
            <form method="GET" action="/anime-shop/public/catalog">
                <input type="text" name="search" placeholder="search" class="input-search" value="<?=  isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';  ?>">
                <!-- Campos ocultos para preservar filtros -->
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
        <li>
            <a href="#user" class="nav-icon-with-text nav-user-info">
                <i class="fa-regular fa-user"></i>
                <span>
                    <span class="user-greeting">¡Bienvenido!</span><br>
                    <span class="user-action">Identifícate / Regístrate</span>
                </span>
            </a>
        </li>
        <li>
            <a href="/anime-shop/public/cart" class="nav-icon-with-text nav-cart-link"> <!-- esta clase nav-cart-link aun no esta agregado al css -->
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Carrito</span>

                <!-- badge numérico  que leerá el js -->
                <span id="mini-cart-count" class="cart-badge"> <!-- esta clase cart-badge aun no esta agregada al css -->
                    <?= $_SESSION['cart_count'] ?? 0; ?>
                </span>
            </a>
        </li>
    </ul>
</nav>
<!-- Barra de navegacion -->