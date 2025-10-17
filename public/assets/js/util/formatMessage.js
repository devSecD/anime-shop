/**
 * Formatea un mensaje de texto con saltos de línea para su inserción segura en el DOM.
 *
 * Esta función toma una cadena de texto que puede contener múltiples líneas separadas por
 * el carácter de nueva línea (`\n`), y genera un `DocumentFragment` que conserva dichos
 * saltos al convertirlos en elementos `<br>`. Esto permite mostrar texto multilínea en
 * elementos HTML sin necesidad de usar `innerHTML`, evitando así riesgos de inyección.
 *
 * @function formatMultilineMessage
 * @export
 * @param {string} [message=''] - El mensaje de texto que se desea formatear. Si no se proporciona, se usa una cadena vacía.
 * @returns {DocumentFragment} Un fragmento de documento que contiene el texto formateado con los saltos de línea representados por etiquetas `<br>`.
 */
export function formatMultilineMessage(message = '') {
    const fragment = document.createDocumentFragment();
    const parts = String(message).split('\n');

    parts.forEach((part, index) => {
        fragment.appendChild(document.createTextNode(part));
        if (index < parts.length - 1) {
            fragment.appendChild(document.createElement('br'));
        }
    });

    return fragment;
}