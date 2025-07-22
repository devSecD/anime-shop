/**
 * Inicializa el toggle de visibilidad para varios elementos al hacer clic en un botón.
 * @param {string} toggleButtonId - ID del botón que hará el toggle.
 * @param {...string} elementIds - IDs de los elementos que se mostrarán/ocultarán.
 */
export function initToggleElementsVisibility(toggleButtonId, ...elementIds) {
    const toggleBtn = document.getElementById(toggleButtonId);
    if (!toggleBtn) return;

    toggleBtn.addEventListener('click', () => {
        elementIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
            }
        });
    });
}
