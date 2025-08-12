<?php
    use App\Helpers\SessionHelper;
    SessionHelper::start();
    $user = SessionHelper::getUser() ;
?>
<!DOCTYPE html>
<html lang="es">
    <?php include $html_head ?>
<body>
    <div class="admin-dashboard">

        <?php include $sidebar ?>
        <!-- Main Content -->
        <main class="main-content">
            <header class="dashboard-header">
            <h1>Panel de Administración</h1>
            <p>Bienvenido, <?= $user["name"] ?></p>
            </header>

            <!-- Summary Cards -->
            <section class="dashboard-cards">
            <div class="card">
                <h3><?= $totalProducts ?></h3>
                <p>Productos</p>
            </div>
            <div class="card">
                <h3><?= $totalOrders ?></h3>
                <p>Órdenes</p>
            </div>
            <div class="card">
                <h3><?= $totalSubscribers ?></h3>
                <p>Suscriptores</p>
            </div>
            <div class="card">
                <h3><?= $pendingOrders ?></h3>
                <p>En espera</p>
                <!--
                Explicación rápida:
                1. 'pending' = órdenes esperando pago o confirmación
                2. 'paid' = pagadas pero aún no enviadas (aún en proceso)
                Los estados 'shipped' y 'cancelled' usualmente no se consideran “en espera” porque ya están en envío o canceladas.
                -->
            </div>
            </section>

            <!-- Products Table -->
            <section class="dashboard-table">
            <h2>Productos recientes</h2>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentProducts as $product): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($product['product_id']) ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td><?= (int)$product['stock'] ?></td>
                            <td>
                                <a href="/anime-shop/public/admin/product/update/<?= $product['product_id'] ?>" title="Editar" class="action-edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentProducts)): ?>
                        <tr>
                            <td colspan="5">No hay productos recientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </section>
        </main>
    </div>
</body>
</html>