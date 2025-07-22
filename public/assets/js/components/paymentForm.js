import { isRadioChecked } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';

export function initPaymentForm() {

    const form = document.getElementById("paymentForm");

    if (!form) {
        console.error("Formulario de pago no encontrado.");
        return;
    }

    form.addEventListener("submit", function (e){
        e.preventDefault();

        let errors = [];

        // Validar dirección de envío
        if (!isRadioChecked('input[name="shipping_address_id"]:checked')) {
            errors.push('Debes seleccionar una dirección de envío.');
        }

        // Validar que el pedido tenga productos
        const orderSummary = document.querySelectorAll('#order-summary li');
        if (!orderSummary || orderSummary.length === 0) {
            errors.push('El carrito está vacío. Agrega productos antes de pagar.');
        }

        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            return;
        }

        // Enviar formulario por AJAX
        sendForm(form, function (response){

            if (!response.success) {
                showToast(response.message, 'error');
            }

            if (response.success && response.redirect) {
                showToast(response.message, 'success');

                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 2000);
            }

        });
    });

}
