/**
 * Actualiza el texto de un botón de wishlist.
 * @param {HTMLElement} btn - El botón de wishlist.
 * @param {boolean} isAdded - true si el producto está en la wishlist.
 */
export function updateWishlistButtonText(btn, isAdded) {
    const span = btn.querySelector(".wishlist-text");
    if (!span) return;
    span.textContent = isAdded
        ? "Quitar de la Wishlist"
        : "Agregar a Wishlist";
}

export function updateWishlistButtonIcon(icon, isAdded) {
    if (!icon) return;

    icon.classList.toggle("active", isAdded);
    icon.classList.toggle("active", isAdded);
    icon.classList.toggle("fa-solid", isAdded);
    icon.classList.toggle("fa-regular", !isAdded);

}