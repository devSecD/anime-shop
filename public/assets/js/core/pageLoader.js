/**
 * ========================================================================
 * Archivo: pageLoader.js
 * Descripción:
 *   Módulo encargado de la carga dinámica de scripts específicos por página.
 *   Permite importar e inicializar únicamente los módulos JavaScript necesarios
 *   según la vista activa, optimizando rendimiento y manteniendo el código modular.
 *
 * Estructura:
 *   - `pageModules`: objeto que mapea cada página con su función de importación
 *     dinámica del módulo correspondiente.
 *   - `loadPageModule(page)`: función que recibe el nombre de la página y ejecuta
 *     el módulo asociado, manejando errores de importación.
 *
 * Notas:
 *   - Cada módulo se encuentra en `public/assets/js/components/`.
 *   - Se recomienda que cada módulo exporte una función de inicialización
 *     con nombre descriptivo (ej. initRegisterForm).
 * ========================================================================
 */

/**
 * Mapa de páginas a módulos JS.
 * Cada propiedad corresponde al nombre de la página y su valor es
 * una función que importa dinámicamente el módulo y ejecuta su inicializador.
 */
const pageModules = {
    register: () => import('../components/registerForm.js').then(m => m.initRegisterForm()),
    login: () => import('../components/loginForm.js').then(m => m.initLoginForm()),
    forgot_password: () => import('../components/forgotPasswordForm.js').then(m => m.initForgotPasswordForm()),
    reset_password: () => import('../components/resetPasswordForm.js').then(m => m.initResetPasswordForm()), 
    shipping_address: () => import('../components/shippingAddressForm.js').then(m => m.initShippingAddressForm()), 
    checkout_payment: () => import('../components/paymentForm.js').then(m => m.initPaymentForm()), 
    registerProduct: () => import('../components/createForm.js').then(m => m.initRegisterProductForm()),
    updateProduct: () => import('../components/updateForm.js').then(m => m.initUpdateProductForm()),
    updateConfiguration: () => import('../components/updateConfiguration.js').then(m => m.initUpdateConfigurationForm()),
    productDetail: () => import('../components/productDetail.js').then(m => m.initProductDetail()),
    wishlist: () => import('../components/wishlistIndex.js').then(m => m.initWishlistIndex()),
    contactForm: () => import('../components/contactForm.js').then(m => m.initContactForm()),
    account: () => import('../components/account.js').then(m => m.initAccount()),
    accountEdit: () => import('../components/accountUpdate.js').then(m => m.initAccountUpdate()),

};

/**
 * Carga e inicializa el módulo JavaScript correspondiente a la página activa.
 *
 * @param {string} page - Identificador de la página (coincide con `data-page` en <main>).
 */
export function loadPageModule(page) {
    if (pageModules[page]) {
        pageModules[page]()
            .catch(err => console.error(`Error cargando el módulo de la página '${page}':`, err));
    } else {
        // Si no hay módulo definido para la página, puedes ignorar o loguear:
        console.warn(`No hay módulo definido para la página '${page}'.`);
    }
}
