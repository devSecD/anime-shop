<?php if (!empty($errors)): ?>
    <p style="color: red;"><?= htmlspecialchars($errors); ?></p>
<?php endif; ?>

<section class="form-container">
    <h2>Cambiar contraseña</h2>
    <form id="resetPasswordForm" action="/anime-shop/public/user/reset-password/process" method="POST" class="form" novalidate>
        <input type="hidden" name="uid" value="<?= htmlspecialchars($userId); ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
        <div class="form-group">
            <label for="password">Nueva contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn-primary">Actualizazr</button>
    </form>
    <script async type="module" src="../../public/assets/js/main.js">
    </script>
</section>