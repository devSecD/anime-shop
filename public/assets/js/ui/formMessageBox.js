/**
 * Crea o actualiza un contenedor de mensajes para formularios.
 * 
 * @param {string} formId - El id del formulario (ej: "contactForm").
 * @param {string} message - Texto a mostrar (permite HTML).
 * @param {boolean} success - Define si es mensaje de éxito o error.
 */
export function updateFormMessage(formId, message, success) {
    const form = document.getElementById(formId);
    if (!form) return;

    let messageBox = document.getElementById(`${formId}-messageBox`);

    // Si no existe, lo creamos dinámicamente antes del form
    if (!messageBox) {
        messageBox = document.createElement("div");
        messageBox.id = `${formId}-messageBox`;
        messageBox.classList.add("form-message");
        // Dentro del mismo contenedor (parentNode del form):
        // Inserta el elemento messageBox antes del formulario (form).
        // Resultado visual: el mensaje aparecerá por encima del formulario.
        form.parentNode.insertBefore(messageBox, form);
    }

    messageBox.innerHTML = message;
    messageBox.classList.toggle("success", success);
    messageBox.classList.toggle("error", !success);
    messageBox.style.display = "block";
}
