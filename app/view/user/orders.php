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
                                <td data-label="Fecha"><?= htmlspecialchars(\App\helpers\DateHelper::formatShort($order['created_at'])) ?></td>
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

                <?php if ($totalPages > 1): ?>
                <aside class="aside-paginated">
                    <ul class="page-list">

                        <?php
                        // Parámetros persistentes (si quieres mantener filtros o búsqueda en futuro)
                        $extraParams = '';
                        if (!empty($_GET)) {
                            $queryParams = $_GET;
                            unset($queryParams['page']);
                            $extraParams = '&' . http_build_query($queryParams);
                        }
                        ?>

                        <!-- Anterior -->
                        <?php if ($currentPage > 1): ?>
                            <li>
                                <a href="?page=<?= $currentPage - 1 . $extraParams; ?>" class="page-previous"></a>
                            </li>
                        <?php endif; ?>

                        <!-- Números de página -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $currentPage): ?>
                                <li><a href="?page=<?= $i . $extraParams; ?>" class="page-current"><?= $i; ?></a></li>
                            <?php else: ?>
                                <li><a href="?page=<?= $i . $extraParams; ?>" class="page-number"><?= $i; ?></a></li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <!-- Siguiente -->
                        <?php if ($currentPage < $totalPages): ?>
                            <li>
                                <a href="?page=<?= $currentPage + 1 . $extraParams; ?>" class="page-next"></a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </aside>
                <?php endif; ?>

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
