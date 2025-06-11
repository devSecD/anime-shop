<section class="form-container">
    <h2>Iniciar sesión</h2>
    <form id="loginForm" action="/anime-shop/public/user/login/process" method="POST" class="form" novalidate>
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary">Ingresar</button>
        <p><a href="/user/recover">¿Olvidaste tu contraseña?</a></p>
    </form>
    <script async type="module" src="../../public/assets/js/main.js">
    </script>
</section>