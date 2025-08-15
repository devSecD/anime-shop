/**
 * Modal genérico reutilizable
 * @param {string} triggerSelector - Selector o selectores de los elementos que abren el modal
 * @param {string} modalSelector - Selector del contenedor modal
 * @param {string} closeSelector - Selector del botón o elemento de cierre
 * @param {function} onOpen - (Opcional) Callback que se ejecuta al abrir el modal
 * @param {function} onClose - (Opcional) Callback que se ejecuta al cerrar el modal
 */
export function initModal({ triggerSelector, modalSelector, closeSelector, onOpen, onClose }) {
    const triggers = document.querySelectorAll(triggerSelector);
    const modal = document.querySelector(modalSelector);
    const closeModal = document.querySelector(closeSelector);

    if (!modal || triggers.length === 0 || !closeModal) return;

    const open = (trigger) => {
        modal.style.display = "block";
        if (onOpen) onOpen(trigger, modal);
    };

    const close = () => {
        modal.style.display = "none";
        if (onClose) onClose(modal);
    };

    triggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            open(trigger);
        });
    });

    closeModal.addEventListener('click', close);

    // Cerrar al hacer clic fuera del contenido
    modal.addEventListener('click', (e) => {
        if (e.target === modal) close();
    });

    // Cerrar con tecla ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
    });
}
