import { localWishlist } from './localWishlist.js';

export function updateHeaderCounter(selector = '#wishlist-count',  userCount = null) {
    const headerCounter = document.querySelector(selector);
    if (!headerCounter) return;

    if (userCount !== null) {
        // Usuario logueado: usar el contador que viene del servidor
        headerCounter.textContent = userCount;
    } else {
        // Invitado: usar localStorage
        headerCounter.textContent = localWishlist.count();
    }
}
