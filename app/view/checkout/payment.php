<section class="checkout-section">
    <h2 class="checkout-title">Pago</h2>

    <form action="/anime-shop/public/checkout/payment/process" method="POST" id="paymentForm" class="form-container" novalidate>
        <!-- Dirección  de envío -->
        <div class="form-group">
            <h3>Dirección  de envío</h3>
            <?php if(!empty($shippingAddresses)): ?>
                <?php foreach($shippingAddresses as $index => $address): ?>
                    <label>
                        <input type="radio" name="shipping_address_id" value="<?= htmlspecialchars($address['address_id']) ?>" <?= $index === 0 ? 'checked' : '' ?>>
                        <?= htmlspecialchars($address['fullname']) ?>, 
                        <?= htmlspecialchars($address['street']) ?>, 
                        <?= htmlspecialchars($address['neighborhood']) ?>, 
                        <?= htmlspecialchars($address['city']) ?>, 
                        <?= htmlspecialchars($address['state']) ?>, 
                        <?= htmlspecialchars($address['postal_code']) ?>, 
                        <?= htmlspecialchars($address['country']) ?>. 
                        Tel: <?= htmlspecialchars($address['phone']) ?>
                    </label><br>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tienes direcciones registradas.</p>
                <a href="/anime-shop/public/checkout/address" class="btn btn-secondary">Agregar dirección</a>
            <?php endif; ?>
        </div>

        <!-- Resumen del pedido -->
        <div class="form-group">
            <h3>Resumen del pedido</h3>
            <ul id="order-summary">
                <?php foreach($items as $productId => $item): ?>
                    <li>
                        <i class="fa-solid fa-cart-shopping" aria-hidden="true" style="margin-right: 6px; color: var(--primary-color);"></i>
                        <?= htmlspecialchars($item['name']) ?> × <?= $item['qty'] ?> - <?= number_format($item['qty'] * $item['price'], 2) ?> MXN
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Total:</strong> <?= number_format($total, 2) ?> MXN</p>
        </div>

        <!-- Acción -->
        <div class="checkout-actions">
            <a href="/anime-shop/public/checkout/address" class="btn btn-secondary">Regresar</a>
            <button type="submit" class="btn btn-primary">Pagar ahora</button>
        </div>
    </form>

    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>
</section>