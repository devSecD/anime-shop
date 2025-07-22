import {
    isNotEmpty,
    isMinLength,
    isValidEmail,
    isValidPhone,
    isValidPostalCode,
    isAlphabeticWithSpaces, 
    isRadioChecked
} from '../../../assets/js/util/validation.js';

import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';
import { initToggleElementsVisibility } from '../../../assets/js/ui/toggleShippingAddressForm.js';


export function initShippingAddressForm() {
    // Inicializar el toggle de mostrar/ocultar para el botón y los formularios
    initToggleElementsVisibility('showNewAddressBtn', 'shippingAddress', 'shippingAddressForm');


    const form = document.getElementById("shippingAddressForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const fullname = form.elements['fullname'].value;
        const phone = form.elements['phone'].value;
        const email = form.elements['email'].value;
        const street = form.elements['street'].value;
        const neighborhood = form.elements['neighborhood'].value;
        const postalCode = form.elements['postal_code'].value;
        const city = form.elements['city'].value;
        const state = form.elements['state'].value;
        const country = form.elements['country'].value;

        let errors = [];

        // Nombre completo
        if (!isNotEmpty(fullname)) {
            errors.push('El nombre completo es obligatorio.');
        } else if (!isAlphabeticWithSpaces(fullname)) {
            errors.push('El nombre completo solo debe contener letras y espacios.');
        } else if (!isMinLength(fullname, 3)) {
            errors.push('El nombre completo debe tener al menos 3 caracteres.');
        }

        // Teléfono
        if (!isNotEmpty(phone)) {
            errors.push('El número telefónico es obligatorio.');
        } else if (!isValidPhone(phone)) {
            errors.push('El teléfono debe contener solo números (10 a 15 dígitos).');
        }

        // Correo electrónico
        if (!isNotEmpty(email)) {
            errors.push('El correo electrónico es obligatorio.');
        } else if (!isValidEmail(email)) {
            errors.push('El correo electrónico no tiene un formato válido.');
        }

        // Calle
        if (!isNotEmpty(street)) {
            errors.push('La calle y número son obligatorios.');
        }

        // Colonia
        if (!isNotEmpty(neighborhood)) {
            errors.push('La colonia es obligatoria.');
        }

        // Código Postal
        if (!isNotEmpty(postalCode)) {
            errors.push('El código postal es obligatorio.');
        } else if (!isValidPostalCode(postalCode)) {
            errors.push('El código postal no tiene un formato válido.');
        }

        // Ciudad
        if (!isNotEmpty(city)) {
            errors.push('La ciudad es obligatoria.');
        }

        // Estado
        if (!isNotEmpty(state)) {
            errors.push('El estado es obligatorio.');
        }

        // País
        if (!isNotEmpty(country)) {
            errors.push('El país es obligatorio.');
        }

        // Mostrar errores
        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            return;
        }

        // Enviar formulario
        sendForm(form, function (response) {
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


    const formShippingAddress = document.querySelector('#shippingAddress'); 
    if (!formShippingAddress) return;

    formShippingAddress.addEventListener("submit", function (e) {
        e.preventDefault();

        let errors = [];

        if (!isRadioChecked('input[name="shipping_address_id"]:checked')) {
            errors.push('Por favor, selecciona una dirección de envío.');
        }

        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            errors = []; // Limpiamos para futuros eventos
            return;
        }

        sendForm(formShippingAddress, function (response) {
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