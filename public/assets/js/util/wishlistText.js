/**
 * Actualiza dinámicamente el texto del botón de la lista de deseos (wishlist)
 * según si el producto ha sido agregado o eliminado.
 *
 * @function updateWishlistButtonText
 * @param {HTMLElement} btn - El botón de wishlist cuyo texto será actualizado.
 * @param {boolean} isAdded - Indica si el producto está actualmente en la wishlist (`true` = agregado, `false` = eliminado).
 * @returns {void} No retorna ningún valor; modifica directamente el contenido del elemento en el DOM.
 */
export function updateWishlistButtonText(btn, isAdded) {
    const span = btn.querySelector(".wishlist-text");
    if (!span) return;
    span.textContent = isAdded
        ? "Quitar de la Wishlist"
        : "Agregar a Wishlist";
}

/**
 * Actualiza el estado visual del ícono de la lista de deseos (wishlist),
 * alternando clases CSS según si el producto está agregado o no.
 *
 * Cambia las clases:
 * - `active`: resalta el ícono cuando el producto está en la wishlist.
 * - `fa-solid` / `fa-regular`: alterna entre estilos de Font Awesome (relleno o contorno).
 *
 * @function updateWishlistButtonIcon
 * @param {HTMLElement} icon - El elemento `<i>` del ícono dentro del botón de wishlist.
 * @param {boolean} isAdded - Indica si el producto está actualmente en la wishlist (`true` = agregado, `false` = eliminado).
 * @returns {void} No retorna ningún valor; modifica las clases del ícono directamente en el DOM.
 */
export function updateWishlistButtonIcon(icon, isAdded) {
    if (!icon) return;

    icon.classList.toggle("active", isAdded);
    icon.classList.toggle("active", isAdded);
    icon.classList.toggle("fa-solid", isAdded);
    icon.classList.toggle("fa-regular", !isAdded);

}