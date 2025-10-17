import { localWishlist } from '../util/localWishlist.js';
import { updateHeaderCounter } from '../util/wishlistCounter.js';
import { sendRequest } from '../ajax/sendRequest.js';
import { initWishlistIcon } from "../components/wishlistIcon.js";
import { updateWishlistButtonText, updateWishlistButtonIcon } from '../util/wishlistText.js';
import { showToast } from '../components/alertToast.js';

/**
 * Inicializa la funcionalidad de wishlist
 * - Maneja agregar/quitar productos
 * - Actualiza contador en header
 * - Aplica efecto visual en el corazón
 */
export function initWishlist() {
    const wishlistButtons = document.querySelectorAll('.btn-wishlist');

    // Inicializa efecto del icono del corazon en los iconos
    initWishlistIcon();

    wishlistButtons.forEach(btn => {
        const productId = parseInt(btn.dataset.productId || btn.dataset.id, 10);
        const isAdded = window.USER_LOGGED_IN
            ? window.USER_WISHLIST.includes(productId)
            : localWishlist.has(productId);

        if (isAdded) btn.classList.add("added");

        updateWishlistButtonText(btn, isAdded);
        updateWishlistButtonIcon(btn.querySelector("i.fa-heart"), isAdded);

        btn.addEventListener('click', function() {
            const productId = parseInt(this.dataset.productId || this.dataset.id);

            if (!window.USER_LOGGED_IN) {
                // Usuario invitado: localStorage
                if (this.classList.contains('added')) { // se elimina producto de la wishlist
                    localWishlist.remove(productId);
                    this.classList.remove('added');
                    updateWishlistButtonText(this, false);
                    updateWishlistButtonIcon(btn.querySelector("i.fa-heart"), false);
                    showToast('Se ha eliminado el producto a la wishlist', 'success')
                } else { // se agrega el producto a la wishlist
                    localWishlist.add(productId);
                    this.classList.add('added');
                    updateWishlistButtonText(this, true);
                    updateWishlistButtonIcon(btn.querySelector("i.fa-heart"), true);
                    showToast('Se ha agregado el producto a la wishlist', 'success')
                }

                // actualiza el contador de la wishlist que se tiene en la barra de navegacion
                updateHeaderCounter();
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
                            updateWishlistButtonText(this, this.classList.contains('added'));
                            updateWishlistButtonIcon(btn.querySelector("i.fa-heart"), this.classList.contains('added'));

                            // Si la acción fue "remove", eliminar el card completo
                            if (action === 'remove') {
                                const card = this.closest('.wishlist-card');
                                if (card) card.remove();
                                showToast('Se ha eliminado el producto a la wishlist', 'success');
                            } else {
                                showToast('Se ha agregado el producto a la wishlist', 'success');
                            }

                        } else {
                            showToast('No se pudo agregar/remover de la wishlist el producto', 'error');
                        }
                    });
            }
        });
    });

    // Inicializar contador al cargar
    if (window.USER_LOGGED_IN && window.USER_WISHLIST_COUNT !== undefined) {
        // Usuario logueado y tiene producto(s) en la wishlist: usar el valor inicial del backend
        updateHeaderCounter('#wishlist-count', window.USER_WISHLIST_COUNT);
    } else {
        // Invitado: usar localStorage
        updateHeaderCounter();
    }
}
