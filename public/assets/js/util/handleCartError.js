import {showToast  } from '../components/alertToast.js';
export function handleCartError(message, type = 'error') {
    const types = {
        validation: 'warning',
        exception: 'error',
        unknown: 'error'
    };
    const toastType = types[type] || 'error';
    showToast(message || 'Ha ocurrido un error', toastType);
}
