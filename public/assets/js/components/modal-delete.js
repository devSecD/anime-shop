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
    const url = new URL(currentDeleteUrl, window.location.origin);
    const productId = url.searchParams.get('id');

    if (!productId) {
        alert('ID de producto no válido.');
        return;
    }

    const res = await sendRequest('/anime-shop/public/admin/products/delete', { product_id: productId });

    if (res.success) {
        if (currentRow) currentRow.remove(); // ✅ UX: eliminar visualmente
        modal.classList.add('hidden');
        currentDeleteUrl = '';
        currentRow = null;
        alert(res.message || 'Producto eliminado.');
    } else {
        alert(res.message || 'Error al eliminar producto.');
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
