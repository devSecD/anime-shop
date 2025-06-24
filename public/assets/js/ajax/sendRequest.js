/**
 * Envía datos vía fetch usando URL‑encoded por defecto.
 *
 * @param {string}   url        – Endpoint absoluto o relativo.
 * @param {Object}   data       – Pares clave‑valor a enviar.
 * @param {Function} callback   – (opcional) Se ejecuta con la respuesta JSON.
 * @param {Object}   options    – { method, contentType } extra.
 */

export async function sendRequest(url, data = {}, callback = null, options = {}) {
    const {
        method = 'POST', 
        contentType = 'application/x-www-form-urlencoded'
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