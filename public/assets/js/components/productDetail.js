import { initModal } from '../components/modal.js';
import { initWishlist } from '../components/wishlist.js'; // <<--- importar nuestro wishlist

export function initProductDetail() {
    initModal({
        triggerSelector: '#main-image',
        modalSelector: '#imageModal',
        closeSelector: '#closeModal',
        onOpen: (trigger, modal) => {
            // Solo si el modal tiene una imagen
            const modalImg = modal.querySelector('#modalImage');
            if (modalImg && trigger.tagName === 'IMG') {
                modalImg.src = trigger.src;
            }
        }
    });

    initWishlist();
}