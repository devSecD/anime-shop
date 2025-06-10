<section class="form-container">
    <h2>Crear una cuenta</h2>
    <form id="registerForm" action="/anime-shop/public/user/register/process" method="POST" class="form" novalidate>
        <div class="form-group">
            <label for="name">Nombre completo</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-group">
                <label for="confirm_password">Confirmar contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password">
        </div>
        <button type="submit" class="btn-primary">Registrarse</button>
        <p>¿Ya tienes una cuenta? <a href="../user/login">Inicia  sesión aquí</a></p>
    </form>
    <script async type="module" src="../../public/assets/js/main.js">
    </script>
</section>