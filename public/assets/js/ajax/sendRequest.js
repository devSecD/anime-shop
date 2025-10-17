/**
 * Envía datos al servidor usando fetch de forma asíncrona,
 * con `application/x-www-form-urlencoded` como formato predeterminado.
 * 
 * @param {string}   url        – Endpoint absoluto o relativo.
 * @param {Object}   data       – Pares clave‑valor a enviar.
 * @param {Function} callback   – (opcional) Se ejecuta con la respuesta JSON.
 * @param {Object}   options    – { method, contentType } extra.
 * 
 * @returns {Promise<Object>} Respuesta JSON del servidor o un objeto de error.
 */

export async function sendRequest(url, data = {}, callback = null, options = {}) {
    // Desestructuración del objeto 'options' recibido como parámetro de la función.
    // Se extraen las propiedades 'method' y 'contentType', asignando valores por defecto
    // en caso de que no estén definidas dentro de 'options'.
    const {
        method = 'POST', // valor por defecto: "POST"
        contentType = 'application/x-www-form-urlencoded' // valor por defecto: "application/x-www-form-urlencoded"
    } = options;

    const body = 
        contentType === 'application/json' 
        ? JSON.stringify(data) 
        : new URLSearchParams(data);
    
        try {
            const res = await fetch(url, { method, headers: { 'Content-Type': contentType }, body });
            const json = await res.json();

            if (callback && typeof callback === 'function') callback(json);
            return json;
        } catch (err) {
            console.error(err);
            const error = { success: false, message: 'Error de red o del servidor' };
            if (callback) callback(error);
            return error;
        }
}