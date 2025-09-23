import { sendRequest } from '../ajax/sendRequest.js';

const modal = document.getElementById('delete-confirmation-modal');
const confirmBtn = document.getElementById('confirm-delete');
const cancelBtn = document.getElementById('cancel-delete');
let currentDeleteUrl = '';
let currentRow = null;

document.querySelectorAll('.action-delete').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        currentDeleteUrl = this.getAttribute('href');
        currentRow = this.closest('tr'); // ✅ fila de tabla (opcional)
        modal.classList.remove('hidden');
    });
});

confirmBtn.addEventListener('click', async () => {

    if (!currentDeleteUrl) {
        alert('URL de eliminación no válida.');
        return;
    }

    const url = new URL(currentDeleteUrl, window.location.origin);

    const id = url.searchParams.get('id');

    if (!id) {
        alert('ID o identificador no válido.');
        return;
    }

    // ✅ Ahora mandamos la petición a la URL que venga del href
    const res = await sendRequest(currentDeleteUrl, { id });

    if (res.success) {
        if (currentRow) {
            if (res.success && res.product_was_deactivated) { 
                currentRow.classList.add('inactive-product'); // fondo gris y opacidad
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
        alert(res.message || 'Eliminado correctamente.');
    } else {
        alert(res.message || 'Error al eliminar.');
    }
});

cancelBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    currentDeleteUrl = '';
    currentRow = null;
});

// Opcional: cerrar al hacer clic fuera del contenido
modal.querySelector('.modal-overlay').addEventListener('click', () => {
    modal.classList.add('hidden');
    currentDeleteUrl = '';
    currentRow = null;
});
