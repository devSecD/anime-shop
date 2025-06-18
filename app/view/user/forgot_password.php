<section class="form-container">
    <h2>Recuperar contraseña</h2>
    <form id="forgotPasswordForm" action="/anime-shop/public/user/passwordrecovery/process" method="POST" class="form" novalidate>
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit" id="forgotPasswordSubmitBtn" class="btn-primary">
            <span class="btn-text">Recuperar contraseña</span>
            <span class="spinner hidden"></span>
        </button>
    </form>
    <script async type="module" src="../../public/assets/js/main.js">
    </script>
</section>