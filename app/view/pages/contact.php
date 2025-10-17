<section class="contact-section">
    <div class="form-container">
        <h2>Contáctanos</h2>
        <form action="/anime-shop/public/contact/submit" method="POST" id="contactForm" novalidate>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nombre <span class="required">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Tu nombre">
                </div>
                <div class="form-group">
                    <label for="email">Correo electrónico <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com">
                </div>
            </div>

            <div class="form-group">
                <label for="subject">Asunto <span class="required">*</span></label>
                <input type="text" id="subject" name="subject" placeholder="Asunto del mensaje">
            </div>

            <div class="form-group">
                <label for="message">Mensaje <span class="required">*</span></label>
                <textarea id="message" name="message" rows="6" placeholder="Escribe tu mensaje aquí"></textarea>
            </div>

            <input type="text" name="website" autocomplete="off" tabindex="-1">

            <button type="submit" class="btn-primary">Enviar mensaje</button>

            <div id="formMessage" class="form-message"></div>
        </form>
    </div>

    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>
    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>

</section>
