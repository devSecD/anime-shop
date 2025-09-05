// newsletterToggle.js
import { sendRequest } from '../../../assets/js/ajax/sendRequest.js';
import { showToast } from '../../../assets/js/components/alertToast.js';

export function initNewsletterToggle() {
    const toggle = document.getElementById('newsletter-toggle');
    const statusText = document.getElementById('newsletter-status');

    if (!toggle || !statusText) return;

    // 1️⃣ Cargar estado inicial del newsletter al inicio
    loadInitialStatus(toggle, statusText);

    // 2️⃣ Configurar evento de cambio
    toggle.addEventListener('change', () => handleToggleChange(toggle, statusText));
}

// Función para obtener el estado inicial desde el backend
async function loadInitialStatus(toggle, statusText) {
    try {
        const res = await sendRequest(
            '/anime-shop/public/user/newsletter/status', // ruta GET para obtener estado
            { method: 'GET' }
        );

        if (res && res.success) {
            toggle.checked = res.subscribed;
            statusText.textContent = res.subscribed ? 'Suscrito' : 'No suscrito';
        } else {
            toggle.checked = false;
            statusText.textContent = 'No suscrito';
        }
    } catch (error) {
        console.error('Error cargando estado del newsletter:', error);
        toggle.checked = false;
        statusText.textContent = 'No suscrito';
    }
}

// Función para manejar cambios de toggle
async function handleToggleChange(toggle, statusText) {
    const previousState = !toggle.checked; // guardar estado previo
    statusText.textContent = toggle.checked ? 'Suscrito' : 'No suscrito';

    try {
        const res = await sendRequest(
            '/anime-shop/public/user/newsletter/toggle', // ruta POST para toggle
            {},
            null,
            { method: 'POST', contentType: 'application/x-www-form-urlencoded' }
        );

        if (res && res.success) {
            toggle.checked = res.subscribed;
            statusText.textContent = res.subscribed ? 'Suscrito' : 'No suscrito';
            showToast(res.message, 'success');
        } else {
            // Revertir estado si falla
            toggle.checked = previousState;
            statusText.textContent = previousState ? 'Suscrito' : 'No suscrito';
            showToast(res?.message || 'Error en el toggle', 'error');
        }
    } catch (error) {
        // Revertir estado si hay error
        toggle.checked = previousState;
        statusText.textContent = previousState ? 'Suscrito' : 'No suscrito';
        console.error('Error en toggle del newsletter:', error);
        showToast('No se pudo actualizar el newsletter.', 'error');
    }
}
