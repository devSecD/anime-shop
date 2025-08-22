// components/wishlist.js
import { localWishlist } from '../util/localWishlist.js';
import { updateHeaderCounter } from '../util/wishlistCounter.js';
import { sendRequest } from '../ajax/sendRequest.js';
import { initWishlistIcon } from "../components/wishlistIcon.js";

/**
 * Inicializa la funcionalidad de wishlist
 * - Maneja agregar/quitar productos
 * - Actualiza contador en header
 * - Aplica efecto visual en el corazón
 */
export function initWishlist() {
    const wishlistButtons = document.querySelectorAll('.btn-wishlist');

    // Inicializa efecto heartbeat en los iconos
    initWishlistIcon();

    wishlistButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = parseInt(this.dataset.productId || this.dataset.id);

            if (!window.USER_LOGGED_IN) {
                // Usuario invitado: localStorage
                if (this.classList.contains('added')) {
                    localWishlist.remove(productId);
                    this.classList.remove('added');
                } else {
                    localWishlist.add(productId);
                    this.classList.add('added');
                }
                updateHeaderCounter(); // usa localWishlist.count()
            } else {
                // Usuario logueado: AJAX
                const action = this.classList.contains('added') ? 'remove' : 'add';
                sendRequest('/anime-shop/public/wishlist/manage', { action, product_id: productId })
                    .then(data => {
                        if (data.success) {
                            // Actualiza el contador del header con el valor que viene del backend
                            updateHeaderCounter('#wishlist-count', data.count);
                            // Actualiza el contador del wishlist index con el valor que viene del backend
                            updateHeaderCounter('#total-products-wishlist-index', data.count);

                            // Alterna clase added
                            this.classList.toggle('added');

                            // Si la acción fue "remove", eliminar el card completo
                            if (action === 'remove') {
                                const card = this.closest('.wishlist-card');
                                if (card) card.remove();
                            }

                        } else {
                            alert(data.message);
                        }
                    });
            }
        });
    });

    // Inicializar contador al cargar
    if (window.USER_LOGGED_IN && window.USER_WISHLIST_COUNT !== undefined) {
        // Usuario logueado: usar el valor inicial del backend
        updateHeaderCounter('#wishlist-count', window.USER_WISHLIST_COUNT);
    } else {
        // Invitado: usar localStorage
        updateHeaderCounter();
    }
}
