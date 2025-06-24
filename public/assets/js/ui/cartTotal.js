const cartTotalEl  = document.getElementById('cart-total');

export function updateCartTotal(total) {
    if (!cartTotalEl ) return;

    cartTotalEl.textContent = total.toFixed(2);

}