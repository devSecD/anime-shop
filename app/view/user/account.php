<!-- app/views/user/account.php -->
<div class="account-page">
    <!-- Información del usuario -->
    <section class="account-section">
        <h1>Mi cuenta</h1>
        <h2>Información del usuario</h2>
        <div class="account-info">
            <p><strong>Nombre:</strong> <?php echo ""; //= htmlspecialchars($userDetails['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($userDetails['email']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($userDetails['phone'] ?? 'No registrado') ?></p>
        </div>
        <div class="account-actions">
            <a href="/anime-shop/public/user/account/edit" class="btn-primary btn-edit-profile">Editar perfil</a>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="account-section">
        <h2>Newsletter</h2>
        <div class="account-newsletter">
            <p>Estado: <span id="newsletter-status"></span></p>
            <label class="switch">
                <input type="checkbox" id="newsletter-toggle">
                <span class="slider round"></span>
            </label>
        </div>
    </section>


    <!-- Pedidos recientes -->
    <section class="orders-section">
        <h1>Mis pedidos</h1>

        <div class="account-orders">
            <?php // if (!empty($orders)): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // foreach ($orders as $order): ?>
                            <tr>
                                <td data-label="ID Pedido">#<?php echo "1"; //= htmlspecialchars($order['order_id']) ?></td>
                                <td data-label="Fecha"><?php echo "04/09/2025"; //= htmlspecialchars($order['created_at']) ?></td>
                                <td data-label="Estado"><?php echo "paid"; //= htmlspecialchars($order['status']) ?></td>
                                <td data-label="Total">$<?php echo "1582.10 MXN"; //= number_format($order['total'], 2) ?></td>
                                <td data-label="Acción">
                                    <a href="/anime-shop/public/user/orderDetail?id=<?php echo "1"; //= urlencode($order['order_id']) ?>" 
                                    class="btn-primary btn-table">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        <?php // endforeach; ?>

                            <tr>
                                <td data-label="ID Pedido">#<?php echo "2"; //= htmlspecialchars($order['order_id']) ?></td>
                                <td data-label="Fecha"><?php echo "01/09/2025"; //= htmlspecialchars($order['created_at']) ?></td>
                                <td data-label="Estado"><?php echo "pending"; //= htmlspecialchars($order['status']) ?></td>
                                <td data-label="Total">$<?php echo "754.23 MXN"; //= number_format($order['total'], 2) ?></td>
                                <td data-label="Acción">
                                    <a href="/anime-shop/public/user/orderDetail?id=<?php echo "1"; //= urlencode($order['order_id']) ?>" 
                                    class="btn-primary btn-table">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>

                    </tbody>
                </table>
            <?php // else: ?>
                <!-- <p>Aún no tienes pedidos registrados.</p> -->
            <?php // endif; ?>
        </div>
    </section>

    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>

</div>