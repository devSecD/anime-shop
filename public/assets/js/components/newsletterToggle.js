import { sendRequest } from '../../../assets/js/ajax/sendRequest.js';
import { showToast } from '../../../assets/js/components/alertToast.js';

export function initNewsletterToggle() {
    const toggle = document.getElementById('newsletter-toggle');
    const statusText = document.getElementById('newsletter-status');

    if (!toggle || !statusText) return;

    // Cargar estado inicial del newsletter al inicio
    loadInitialStatus(toggle, statusText);

    // Ejecutar la función handleToggleChange cada vez que el usuario cambie el estado del toggle
    toggle.addEventListener('change', () => handleToggleChange(toggle, statusText));
}

// Función para obtener el estado inicial desde el backend
async function loadInitialStatus(toggle, statusText) {
    try {
        const res = await sendRequest(
            '/anime-shop/public/user/newsletter/status',
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
        toggle.checked = false;
        statusText.textContent = 'No suscrito';
    }
}

// Función para manejar cambios de toggle
async function handleToggleChange(toggle, statusText) {
    const previousState = !toggle.checked; // guardar el estado previo del toggle antes de la petición AJAX (lo contrario de su valor actual)
    statusText.textContent = toggle.checked ? 'Suscrito' : 'No suscrito';

    try {
        const res = await sendRequest(
            '/anime-shop/public/user/newsletter/toggle',
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
        showToast('No se pudo actualizar el newsletter.', 'error');
    }
}
