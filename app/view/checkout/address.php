<section class="checkout-section">
    <h2 class="checkout-title">Dirección de envío</h2>
        <?php if(!empty($shippingAddresses)): ?>
            <form action="/anime-shop/public/checkout/address/confirmShippingAddress" method="POST" id="shippingAddress" class="form-container" novalidate style="display: <?php echo !empty($shippingAddresses) ? 'block' : 'none'; ?>;">
            <p>Debes de seleccionar un dirección de envío</p>
                <?php foreach($shippingAddresses as $index => $address): ?>
                    <label>
                        <input type="radio" name="shipping_address_id" value="<?= htmlspecialchars($address['address_id']) ?>">
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

                <div class="checkout-actions">
                    <a href="/anime-shop/public/checkout" class="btn btn-secondary">Regresar</a>
                    <button type="submit" class="btn btn-primary">Continuar al pago</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Botón para mostrar el formulario -->
        <div class="checkout-actions">
            <button type="button" class="btn btn-secondary" id="showNewAddressBtn">
                Agregar nueva dirección
            </button>
        </div>

        <form action="/anime-shop/public/checkout/address/createShippingAddressForm" method="POST" id="shippingAddressForm" class="form-container" novalidate style="display: <?php echo empty($shippingAddresses) ? 'block' : 'none'; ?>;">
            <div class="form-group">
                <label for="fullname">Nombre completo</label>
                <input type="text" name="fullname" id="fullname" required>
            </div>

            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="tel" name="phone" id="phone" placeholder=" Ingrese su número completo con lada/internacional (sin espacios ni símbolos)." required>
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="street">Calle y número</label>
                <input type="text" name="street" id="street" required>
            </div>

            <div class="form-group">
                <label for="neighborhood">Colonia</label>
                <input type="text" name="neighborhood" id="neighborhood" required>
            </div>

            <div class="form-group">
                <label for="postal_code">Código Postal</label>
                <input type="text" name="postal_code" id="postal_code" required>
            </div>

            <div class="form-group">
                <label for="city">Ciudad</label>
                <input type="text" name="city" id="city" required>
            </div>

            <div class="form-group">
                <label for="state">Estado</label>
                <input type="text" name="state" id="state" required>
            </div>

            <div class="form-group">
                <label for="country">País</label>
                <input type="text" name="country" id="country" required>
            </div>

            <div class="form-group">
                <label for="notes">Notas adicionales</label>
                <textarea name="notes" id="notes" rows="3"></textarea>
            </div>

            <div class="checkout-actions">
                <a href="/anime-shop/public/checkout" class="btn btn-secondary">Regresar</a>
                <button type="submit" class="btn btn-primary">Continuar al pago</button>
            </div>
        </form>
    <script>

    </script>
    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>
</section>