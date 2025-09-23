<?php
    use App\Helpers\SessionHelper;
    SessionHelper::start();
    $user = SessionHelper::getUser();
?>
<!-- Botón hamburguesa -->
<button class="sidebar-toggle-btn" id="sidebarToggle">
    <i class="fa fa-bars"></i>
</button>
<aside class="sidebar sidebar-hidden" id="sidebarMenu">
    <a href="/anime-shop/public/admin/dashboard">
        <h2>Admin</h2>
        <h2>Anime Shop</h2>
    </a>

    <nav>
        <ul>
            <li>
                <a href="#"><i class="fa fa-box"></i> Productos</a>
                <ul class="submenu">
                    <li><a href="/anime-shop/public/admin/products/create"><i class="fa fa-plus-circle"></i> Agregar nuevo</a></li>
                    <li><a href="/anime-shop/public/admin/products"><i class="fa fa-list"></i> Ver todos</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-shopping-cart"></i> Órdenes</a>
                <ul class="submenu">
                    <li><a href="/anime-shop/public/admin/orders"><i class="fa fa-list"></i> Ver todos</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-users"></i> Usuarios</a>
                <ul class="submenu">
                    <li><a href="/anime-shop/public/admin/user"><i class="fa fa-list"></i> Ver todos</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-envelope"></i> Newsletter</a>
                <ul class="submenu">
                    <li><a href="/anime-shop/public/admin/newsletters"><i class="fa fa-list"></i> Ver todos</a></li>
                </ul>
            </li>
            <?php if(in_array( 'superadmin', $user['roles'])): ?>
                <li>
                    <a href="/anime-shop/public/admin/setting"><i class="fa fa-cogs"></i> Configuración</a>
                </li>
            <?php endif; ?>
            <li>
                <a href="/anime-shop/public/user/logout">
                    <i class="fa fa-sign-out-alt"></i> Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>
</aside>

<script>
    const sidebar = document.getElementById('sidebarMenu');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-hidden');
    });

    // Opcional: cerrar al hacer clic fuera del sidebar en móviles
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !toggleBtn.contains(e.target)) {
            sidebar.classList.add('sidebar-hidden');
        }
    });
</script>