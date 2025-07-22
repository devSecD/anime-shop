<?php
// Recibe: $orderData, $paymentId
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago rechazado</title>
    <link rel="stylesheet" href="/anime-shop/public/assets/css/components/payment.css">
</head>
<body>
    <section class="payment-container">
        <h1>❌ Pago rechazado</h1>
        <?php if ($orderData): ?>
            <p>Tu orden #<?php echo htmlspecialchars($orderData['order_id']); ?> no pudo ser procesada.</p>
            <p>Por favor intenta nuevamente o contáctanos si el problema persiste.</p>
        <?php else: ?>
            <p>No pudimos encontrar los datos de tu orden.</p>
        <?php endif; ?>
        <p>ID de pago en Mercado Pago: <strong><?php echo htmlspecialchars($paymentId); ?></strong></p>
        <a href="/anime-shop/public/" class="btn-home">🏠 Volver a la tienda</a>
    </section>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>
</body>
</html>
