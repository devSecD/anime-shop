<div class="account-page">
    <!-- Pedidos del usuario -->
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
                <p>Aún no tienes pedidos registrados.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- <script async type="module" src="/anime-shop/public/assets/js/main.js"></script> -->
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>

</div>
