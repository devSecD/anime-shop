import { sendRequest } from '../../../assets/js/ajax/sendRequest.js';
import { showToast } from '../../../assets/js/components/alertToast.js';
import { updateCartCounter } from '../../../assets/js/ui/updateCartCounter.js';
import { updateCartTotal } from '../../../assets/js/ui/cartTotal.js';
import { loadCartCount } from './loadCartCount.js';
import { handleCartError } from '../../../assets/js/util/handleCartError.js';


loadCartCount();

/** Delegación global para clicks */
document.addEventListener('click', async e => {
    /* Añadir al carrito desde catalogo*/
    if (e.target.matches('.btn-add-to-cart')) {
        const id = e.target.dataset.id;
        const qty = parseInt(e.target.dataset.qty || '1', 10);
        const res = await sendRequest('/anime-shop/public/cart/add', {product_id: id, qty}); // /anime-shop/public/user/login/process
        console.log(res);
        if (res.success) {
            updateCartCounter(res.count);
            updateCartTotal(res.total);
            showToast(res.message || 'Producto añadido al carrito');
        } else {
            handleCartError(res.message, res.error_type);
        }
    }

    /** Eliminar del carrito  */
    if (e.target.matches('.cart-remove')) {
        const id = e.target.dataset.id;
        const res = await sendRequest('/anime-shop/public/cart/remove', {product_id: id});

        if (res.success) {
            updateCartCounter(res.count);
            e.target.closest('tr')?.remove();
            updateCartTotal(res.total);
            showToast('Producto eliminado');
        } else {
            handleCartError(res.message, res.error_type);
        }
    }
});

/** Cambiar cantidad dentro del carrito */
document.addEventListener('change', async e => {
    if (e.target.matches('.cart-qty-input')) {
        const id = e.target.dataset.id;
        const qty = parseInt(e.target.value || '1', 10);

        const res = await sendRequest('/anime-shop/public/cart/update', {product_id: id, qty});

        if (res.success) {
            updateCartCounter(res.count);
            updateCartTotal(res.total);
            // Si tienes cálculo de subtotales por JS, actualízalos aquí;
            // de lo contrario, recarga para reflejar cambios:
            location.reload();
        } else {
            handleCartError(res.message, res.error_type);
        }
    }
});