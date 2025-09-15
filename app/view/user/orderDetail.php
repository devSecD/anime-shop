<div class="account-page">
    <section class="orders-section order-detail">
        <h1>Detalle del pedido #<?= htmlspecialchars($order['order_id']) ?></h1>

        <div class="order-info">
            <p><strong>Fecha:</strong> <?= htmlspecialchars(\App\helpers\DateHelper::formatLong($order['created_at'])) ?></p>
            <p><strong>Estado:</strong> <?= htmlspecialchars($order['status']) ?></p>
            <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
            <p><strong>Dirección de envío:</strong> <?= \App\Helpers\OrderHelper::getFullShippingAddress($order); ?></p>
        </div>

        <h2><?= \App\Helpers\OrderHelper::getStatusText($order['status']); ?></h2>

        <table class="orders-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td data-label="Imagen">
                        <a href="/anime-shop/public/product/detail?product_id=<?= $item['product_id'] ?>&product_name=<?= urlencode($item['name']) ?>" title="Ir al detalle del producto">
                            <img src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img">
                        </a>
                    </td>
                    <td data-label="Producto">
                        <a href="/anime-shop/public/product/detail?product_id=<?= $item['product_id'] ?>&product_name=<?= urlencode($item['name']) ?>" class="product-link" title="Ir al detalle del producto">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                    </td>
                    <td data-label="Cantidad"><?= $item['quantity'] ?></td>
                    <td data-label="Precio">$<?= number_format($item['price'], 2) ?></td>
                    <td data-label="Subtotal">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="/anime-shop/public/user/orders" class="btn-primary back-orders">Volver a mis pedidos</a>

        <?php if (in_array('cart', $assets)): ?>
            <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
        <?php endif; ?>

    </section>
</div>
