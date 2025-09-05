import { isNotEmpty, 
        isAlphabeticWithSpaces, 
        isTextLengthBetween, 
        isValidEmail, 
        isValidPhone, 
        isMinLength, 
        areEqual } from "../../../assets/js/util/validation.js";
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from "../../../assets/js/components/alertToast.js";

export function initAccountUpdate() {

    const form = document.getElementById("accountUpdateForm");

    form.addEventListener("submit", async function (e){

        e.preventDefault();

        const name = form.elements['name'].value;
        const email = form.elements['email'].value;
        const phone = form.elements['phone'].value;
        const password = form.elements['password'].value;
        const confirmPassword = form.elements['password_confirm'].value;

        let errors = [];

        if (!isNotEmpty(name))
            errors.push('El nombre es obligatorio.');
        else if(!isAlphabeticWithSpaces(name))
            errors.push('El nombre solo puede contener letras y espacios.');
        else if (!isTextLengthBetween(name, 2, 100))
            errors.push('El nombre debe tener entre 2 y 100 caracteres.');

        if (!isNotEmpty(email))
            errors.push('El correo electrónico es obligatorio.');
        else if (!isValidEmail(email))
            errors.push('El correo electrónico no es válido.');

        if (isNotEmpty(phone) && !isValidPhone(phone))
            errors.push('El teléfono debe contener entre 10 y 15 dígitos numéricos.');

        if (isNotEmpty(password)) {
            if (!isMinLength(password, 6))
                errors.push('La contraseña debe tener al menos 6 caracteres.');
            if (!areEqual(password, confirmPassword))
                errors.push('Las contraseñas no coinciden.');
        }

        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            return;
        }

        // Enviar formulario con AJAX
        sendForm(form, function (response) {
            if (!response.success) {
                showToast(response.message, 'error');
            }

            if (response.success && response.redirect) {
                showToast(response.message, 'success');

                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 3000);
            }
        });

    });
}