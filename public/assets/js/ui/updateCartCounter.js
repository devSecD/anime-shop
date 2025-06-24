/**
 * Actualiza el numerito del carrito (#mini-cart-count)
 * @param {number} count - Cantidad total de ítems
 */
export function updateCartCounter(count) {
    const counter = document.getElementById('mini-cart-count');
    if (!counter) return;

    if (count > 0) {
        counter.textContent = count;
        counter.style.display = 'block';
    } else {
        counter.textContent = '';
        counter.style.display = 'none';
    }
}
