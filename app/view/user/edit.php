<section class="account-section">
    <div class="form-container">
        <h2>Editar perfil</h2>

        <form id="accountUpdateForm" action="/anime-shop/public/user/account/update" method="post" class="account-form" novalidate>
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="name" value="<?= htmlspecialchars($userDetails['name']) ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($userDetails['email']) ?>">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($userDetails['phone'] ?? '') ?>">
            </div>

            <div class="form-group optional">
                <label>
                    Nueva contraseña <span class="optional-tag">(opcional)</span>
                    <input type="password" name="password" autocomplete="new-password" placeholder="Dejar vacío si no deseas cambiar">
                </label>
                <label>
                    Confirmar nueva contraseña
                    <input type="password" name="password_confirm" autocomplete="new-password" placeholder="Reingresa la contraseña">
                </label>

            </div>

            <button type="submit" class="btn-primary">Guardar cambios</button>
            <a href="/anime-shop/public/user/account" class="btn-cancel">Cancelar</a>
        </form>
    </div>
    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>
</section>
