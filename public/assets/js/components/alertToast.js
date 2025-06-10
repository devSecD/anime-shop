import { formatMultilineMessage } from '../util/formatMessage.js';

export function showToast(message = '', type = 'success', duration = 3000) {

    if (!message || String(message).trim() === '') return;
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    toast.appendChild(formatMultilineMessage(message));
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout( () => toast.remove(), 500 );
    }, duration);
}