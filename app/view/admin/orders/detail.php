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
                <h1>Detalle de la orden</h1>
                <p>Bienvenido, <?= $user["name"] ?></p>
            </header>

            <div class="admin-container order-detail">
                <h1 class="order-title">Orden #<?= htmlspecialchars($order['order_id']) ?></h1>

                <!-- Información general + Dirección de envío en 2 columnas -->
                <div class="order-info-grid">
                    <!-- Información general de la orden -->
                    <div class="order-section">
                        <h2 class="section-title">Información general</h2>
                        <p><strong>Usuario:</strong> <?= htmlspecialchars($order['user_name']) ?> (<?= htmlspecialchars($order['user_email']) ?>)</p>
                        <p><strong>Fecha:</strong> <?= \App\helpers\DateHelper::formatShort($order['created_at']); ?></p>
                        <p><strong>Estado:</strong> 
                            <span class="order-status status-<?= htmlspecialchars($order['status']) ?>">
                                <?= ucfirst(htmlspecialchars($order['status'])) ?>
                                <?php if (!empty($order['status_original'])): ?>
                                    <span class="tooltip-original">
                                        Estatus original de Mercado Pago: <?= ucfirst(htmlspecialchars($order['status_original'])) ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                        </p>
                        <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
                    </div>

                    <!-- Dirección de envío -->
                    <div class="order-section">
                        <h2 class="section-title">Dirección de envío</h2>
                        <p><strong>Nombre:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($order['sa_email']) ?></p>
                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($order['sa_phone']) ?></p>
                        <p><strong>Calle:</strong> <?= htmlspecialchars($order['street']) ?></p>
                        <p><strong>Colonia:</strong> <?= htmlspecialchars($order['neighborhood']) ?></p>
                        <p><strong>Ciudad y Estado:</strong> <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?></p>
                        <p><strong>Código Postal:</strong> <?= htmlspecialchars($order['postal_code']) ?></p>
                        <p><strong>País:</strong> <?= htmlspecialchars($order['country']) ?></p>
                    </div>
                </div>

                <!-- Productos de la orden -->
                <div class="order-section">
                    <h2 class="section-title">Productos</h2>
                    <table class="order-items-table">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Subtotal</th>
                                <th>Stock actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <?php else: ?>
                                            <span>Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                    <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                    <td><?= htmlspecialchars($item['stock']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="order-actions">
                    <a href="/anime-shop/public/admin/orders" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Volver a órdenes
                    </a>
                </div>
            </div>

        </main>
    </div>
</body>
</html>
