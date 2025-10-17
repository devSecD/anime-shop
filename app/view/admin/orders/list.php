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
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Panel de administración: Órdenes</h1>
                <p>Bienvenido, <?= $user["name"] ?></p>
            </header>

            <div style="overflow-x: auto;">
                <table class="admin-products-table">
                    <thead>
                        <tr>
                            <th>ID Orden</th>
                            <th>Usuario</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="6" class="text-center no-products">No hay órdenes registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($orders as $order): ?>
                                <tr>
                                    <td data-label="ID Orden"><?= htmlspecialchars($order['order_id']) ?></td>
                                    <td data-label="Usuario"><?= htmlspecialchars($order['user_name']) ?></td>
                                    <td data-label="Total">$<?= number_format($order['total'], 2) ?></td>
                                    <td data-label="Estado"><?= ucfirst(htmlspecialchars($order['status'])) ?></td>
                                    <td data-label="Fecha"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td data-label="Acciones">
                                        <a href="/anime-shop/public/admin/order/detail?order_id=<?= $order['order_id'] ?>" title="Ver detalle" class="action-edit">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php
                $queryParams = [];
                if ($status) $queryParams['status'] = $status;

                include __DIR__ . '/../../../view/admin/components/pagination.php';
            ?>

            </div>

        </main>
    </div>
</body>
</html>
