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

            <!--
            Notas:
            * Los checkboxes (is_on_sale e is_preorder) solo enviarán el valor "1" si están marcados.
            * El campo price_discounted es opcional (no required), así puedes dejarlo vacío si el producto no tiene descuento.
            * Los campos como sold_count, created_at, y product_id no se incluyen en el formulario ya que:
                * product_id lo genera la base de datos automáticamente.
                * sold_count inicia en 0 por defecto.
                * created_at es un timestamp automático.
            -->
            <?php include $product_from ?>

        </main>
    </div>

    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
</body>
</html>