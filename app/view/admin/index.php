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
                <a href="/anime-shop/public/admin/products" class="card" title="Ver todos los productos">
                    <h3><?= $totalProducts ?></h3>
                    <p>Productos</p>
                </a>
                <a href="/anime-shop/public/admin/orders" class="card" title="Ver todas las órdenes">
                    <h3><?= $totalOrders ?></h3>
                    <p>Órdenes</p>
                </a>
                <a href="/anime-shop/public/admin/newsletters" class="card" title="Ver todos los suscriptores">
                    <h3><?= $totalSubscribers ?></h3>
                    <p>Suscriptores</p>
                </a>
                <a href="/anime-shop/public/admin/orders?status=pending" class="card" title="Ver órdenes pendientes de pago">
                    <h3><?= $pendingOrders ?></h3>
                    <p>Pendientes de pago</p>
                </a>
                <a href="#" class="card" >
                    <h3><?= $soldCountTotal ?></h3>
                    <p>Recuento total vendido</p>
                </a>
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
                            <td data-label="ID">#<?= htmlspecialchars($product['product_id']) ?></td>
                            <td data-label="Nombre"><?= htmlspecialchars($product['name']) ?></td>
                            <td data-label="Precio">$<?= number_format($product['price'], 2) ?></td>
                            <td data-label="Stock"><?= (int)$product['stock'] ?></td>
                            <td data-label="Acciones">
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