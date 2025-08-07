<!DOCTYPE html>
<html lang="es">
    <?php include $html_head ?>
<body>

    <div class="admin-dashboard">
        <?php include $sidebar ?>
        <main class="main-content" data-page="<?= $page ?? '' ?>">
            <header class="dashboard-header">
                <h1><?= $title ?></h1>
                <p>Bienvenido, Admin</p>
            </header>

            <?php if (!empty($error)): ?>
                <div class="form-container">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <?php include $product_from ?>
        </main>
    </div>

    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
</body>
</html>