<?php
// Recibe: $orderData, $paymentId
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago pendiente</title>
    <link rel="stylesheet" href="/anime-shop/public/assets/css/components/payment.css">
</head>
<body>
    <section class="payment-container">
        <h1>⏳ Pago pendiente</h1>
        <?php if ($orderData): ?>
            <p>Tu orden #<?php echo htmlspecialchars($orderData['order_id']); ?> está pendiente de confirmación.</p>
            <p>Te avisaremos por correo cuando el pago sea aprobado.</p>
        <?php else: ?>
            <p>Tu pago está pendiente, pero no pudimos encontrar los datos de la orden.</p>
        <?php endif; ?>
        <p>ID de pago en Mercado Pago: <strong><?php echo htmlspecialchars($paymentId); ?></strong></p>
        <a href="/anime-shop/public/" class="btn-home">🏠 Volver a la tienda</a>
    </section>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>
</body>
</html>
