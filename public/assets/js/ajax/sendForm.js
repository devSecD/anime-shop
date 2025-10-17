/**
 * Envía los datos de un formulario por AJAX usando fetch de forma asíncrona.
 *
 * Convierte automáticamente los campos del formulario en FormData y envía
 * la petición al endpoint definido en `formElement.action`. Retorna la
 * respuesta JSON y ejecuta un callback opcional si se proporciona.
 *
 * @param {HTMLFormElement} formElement - Elemento del formulario a enviar.
 * @param {Function} [callback]         - Función opcional que se ejecuta con la respuesta JSON del servidor.
 *
 * @returns {Promise<void>} La función retorna una promesa que se resuelve
 *                          cuando se recibe la respuesta o ocurre un error.
 *
 * Notas:
 * - En caso de error de red o servidor, el callback recibe un objeto con
 *   la estructura: { success: false, message: "Error de red o del servidor" }.
 * - Se puede combinar con `sendRequest` para estandarizar peticiones AJAX
 *   en proyectos más grandes.
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