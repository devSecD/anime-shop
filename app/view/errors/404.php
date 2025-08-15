<main class="error404">
    <figure class="error404__art" aria-hidden="true">
        <img
        src="/anime-shop/public/assets/images/errors/404-anime-ocean.png"
        alt=""
        loading="eager"
        decoding="async"
        >
    </figure>

    <section class="error404__content">
        <h1 class="error404__title">404</h1>
        <p class="error404__subtitle">¡Ups! El tesoro que buscas no está en esta isla.</p>
        <p class="error404__text">
        La página (o producto) no fue encontrada. Tal vez fue movida, renombrada,
        o el mapa tiene una ruta equivocada.
        </p>

        <nav class="error404__actions">
        <a class="btn btn--primary" href="/anime-shop/public/">
            ⛵ Volver al inicio
        </a>
        <a class="btn btn--outline" href="/anime-shop/public/catalog">
            🗺️ Ir al catálogo
        </a>
        <button class="btn btn--ghost" onclick="history.back()">
            🔙 Regresar
        </button>
        </nav>
    </section>
    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
    <?php if (in_array('cart', $assets)): ?>
        <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
    <?php endif; ?>
</main>
