/**
 * Inicializa la animación y el comportamiento visual del ícono de la lista de deseos (wishlist).
 * 
 * Esta función busca todos los botones que coincidan con el selector indicado
 * (por defecto `.btn-wishlist`) y les asigna un evento `click`.  
 * 
 * Al hacer clic:
 * - Se reproduce una animación tipo “latido” (clase `heartbeat`).
 * - Se alterna el color del ícono mediante la clase `active`.
 * - Se cambia el estilo del ícono entre `fa-regular` y `fa-solid` (Font Awesome),
 *   simulando un “agregado o quitado de favoritos”.
 *
 * @function initWishlistIcon
 * @param {string} [selector=".btn-wishlist"] - Selector CSS que identifica los botones de wishlist.
 * @returns {void} No retorna ningún valor. Modifica directamente los elementos del DOM.
 */
export function initWishlistIcon(selector = ".btn-wishlist") {
  const buttons = document.querySelectorAll(selector);

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      const icon = btn.querySelector("i");
      if (!icon) return;

      // Animación heartbeat ("latido")
      icon.classList.remove("heartbeat");
      void icon.offsetWidth; // force reflow
      icon.classList.add("heartbeat");

      // Toggle color con clase .active
      icon.classList.toggle("active");

      // Toggle entre fa-regular y fa-solid
      icon.classList.toggle("fa-regular");
      icon.classList.toggle("fa-solid");
    });
  });
}
