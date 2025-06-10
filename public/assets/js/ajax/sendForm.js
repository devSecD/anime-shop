/**
 * Envía datos de formulario por AJAX usando fetch.
 * @param {HTMLFormElement} formElement - El formulario a enviar.
 * @param {Function} callback - Función que se ejecuta con la respuesta del servidor.
 */

export async function sendForm(formElement, callback) {
    const url = formElement.action;
    const formData = new FormData(formElement);

    try {
        const response = await fetch(url, {
            method: "POST", 
            body: formData
        });

        const data = await response.json();

        if (callback && typeof callback === "function") {
            callback(data);
        }
    } catch (error) {
        console.error(error);
        callback({ success: false, message: "Error de red o del servidor"});
    }
}