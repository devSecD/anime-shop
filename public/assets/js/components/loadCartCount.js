import { sendRequest } from '../../../assets/js/ajax/sendRequest.js';
import { updateCartCounter } from '../ui/updateCartCounter.js';

/**
 * Carga el contador de productos del carrito desde el backend
 * y actualiza el contador visual en el DOM.
 */

export async function loadCartCount() {
    try {
        const res = await sendRequest('/anime-shop/public/cart/count', {});
        updateCartCounter(res.count ?? 0);
    } catch (error) {
        console.error('Error al cargar el contador del carrito', error);
        updateCartCounter(0);
    }
}