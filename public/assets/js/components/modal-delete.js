/**
 * Este script gestiona la lógica del modal de confirmación de eliminación para elementos de tipo producto,
 * suscripciones u otros registros. Permite confirmar o cancelar la eliminación mediante una interfaz modal
 * y ejecuta una petición AJAX al servidor para realizar la acción correspondiente.
 *
 * Dependencias:
 * - sendRequest (importado desde '../ajax/sendRequest.js'): realiza peticiones AJAX asíncronas.
 * - showToast (importado desde 'alertToast.js'): mostrará notificaciones no intrusivas (pendiente de implementación).
 *
 * Elementos HTML involucrados:
 * - #delete-confirmation-modal → modal principal de confirmación.
 * - #confirm-delete → botón que confirma la eliminación.
 * - #cancel-delete → botón que cancela la eliminación.
 * - .action-delete → enlaces o botones que disparan la apertura del modal.
 *
 * Flujo general:
 * 1. El usuario hace clic en un enlace con clase `.action-delete`.
 * 2. Se guarda la URL del enlace (que contiene el id del elemento a eliminar) y la referencia a la fila `<tr>` correspondiente.
 * 3. Se muestra el modal de confirmación.
 * 4. Si el usuario confirma:
 *      - Se extrae el parámetro `id` de la URL.
 *      - Se envía una solicitud AJAX con el ID al backend usando `sendRequest`.
 *      - Si la respuesta es exitosa, se actualiza la interfaz:
 *          - Si el producto fue desactivado (`product_was_deactivated`): se marca la fila como inactiva.
 *          - En otros casos, se elimina la fila del DOM.
 *      - Se cierra el modal y se muestra un mensaje de éxito (featured: showToast).
 * 5. Si el usuario cancela o hace clic fuera del modal, se oculta la ventana sin ejecutar ninguna acción.
 */

import { sendRequest } from '../ajax/sendRequest.js';
// agregar el import de la funcion "showToast" que viene del js alertToast.js

const modal = document.getElementById('delete-confirmation-modal');
const confirmBtn = document.getElementById('confirm-delete');
const cancelBtn = document.getElementById('cancel-delete');
let currentDeleteUrl = '';
let currentRow = null;

document.querySelectorAll('.action-delete').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        currentDeleteUrl = this.getAttribute('href');
        currentRow = this.closest('tr'); // fila de tabla
        modal.classList.remove('hidden'); // muestra la modal
    });
});

confirmBtn.addEventListener('click', async () => {

    if (!currentDeleteUrl) {
        alert('URL de eliminación no válida.');  // reemplazar por un showToast
        return;
    }

    const url = new URL(currentDeleteUrl, window.location.origin);

    const id = url.searchParams.get('id');

    if (!id) {
        alert('ID o identificador no válido.'); // reemplazar por un showToast
        return;
    }

    // Ahora mandamos la petición a la URL que venga del href
    const res = await sendRequest(currentDeleteUrl, { id });

    if (res.success) {
        if (currentRow) {
            if (res.success && res.product_was_deactivated) { 
                currentRow.classList.add('inactive-product'); // fondo gris y opacidad (fila)
                const statusCell = currentRow.querySelector('.status-cell');
                if (statusCell)  {
                    // Solo marcar como Inactivo si el producto fue desactivado
                    statusCell.textContent = 'Inactivo';
                    statusCell.classList.add('inactive-text');
                }
            } else {
                // Caso newsletter u otros: simplemente removemos la fila
                currentRow.remove();
            }
        }
        modal.classList.add('hidden');
        currentDeleteUrl = '';
        currentRow = null;
        alert(res.message || 'Eliminado correctamente.'); // reemplazar por un showToast
    } else {
        alert(res.message || 'Error al eliminar.'); // reemplazar por un showToast
    }
});

cancelBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    currentDeleteUrl = '';
    currentRow = null;
});

// Cerrar al hacer clic fuera del contenido
modal.querySelector('.modal-overlay').addEventListener('click', () => {
    modal.classList.add('hidden');
    currentDeleteUrl = '';
    currentRow = null;
});
