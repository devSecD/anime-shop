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
            <?php if (!empty($orders)): ?>
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
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td data-label="ID Pedido">#<?= htmlspecialchars($order['order_id']) ?></td>
                                <td data-label="Fecha"><?= htmlspecialchars($order['created_at']) ?></td>
                                <td data-label="Estado"><?= htmlspecialchars($order['status']) ?></td>
                                <td data-label="Total">$<?= number_format($order['total'], 2) ?></td>
                                <td data-label="Acción">
                                    <a href="/anime-shop/public/user/orderDetail?id=<?= urlencode($order['order_id']) ?>" 
                                    class="btn-primary btn-table">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- <p>Aún no tienes pedidos registrados.</p> -->
            <?php endif; ?>
        </div>
    </section>

    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>

</div>