<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Gracias por tu compra!</title>
    <link rel="stylesheet" href="/anime-shop/public/assets/css/components/payment.css">
</head>
<body>
    <section class="payment-container">
        <div class="payment-header">
            <h1>✅ ¡Gracias por tu compra!</h1>
            <p>ID de pago en Mercado Pago: <strong><?php echo htmlspecialchars($paymentId); ?></strong></p>
        </div>

        <?php if ($orderData): ?>
            <div class="order-summary">
                <h2>Resumen de tu orden #<?php echo htmlspecialchars($orderData['order_id']); ?></h2>
                <div class="order-info">
                    <p><strong>Estado de la orden:</strong> <?php echo htmlspecialchars($orderData['status']); ?></p>
                    <p><strong>Total pagado:</strong> $<?php echo htmlspecialchars(number_format($orderData['total'], 2)); ?> MXN</p>
                </div>

                <?php if (!empty($orderData['items'])): ?>
                    <div class="items-list">
                        <?php foreach ($orderData['items'] as $item): ?>
                            <div class="item">
                                <img src="/anime-shop/public/assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div class="item-details">
                                    <p><?php echo htmlspecialchars($item['name']); ?></p>
                                    <p>Cantidad: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                    <p>Subtotal: $<?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>No pudimos encontrar la información de tu orden. Por favor contacta al soporte.</p>
        <?php endif; ?>

        <a href="/anime-shop/public/" class="btn-home">🏠 Volver a la tienda</a>
    </section>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>
</body>
</html>
