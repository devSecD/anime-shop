import { isNotEmpty, isMinLength, isValidEmail, areEqual } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';

export function initRegisterForm() {

    const form = document.getElementById("registerForm");

    form.addEventListener("submit", function (e){
        e.preventDefault();
        /*
        const name = form.elements['name'].value;
        const email = form.elements['email'].value;
        const password = form.elements['password'].value;
        const confirmPassword = form.elements['confirm_password'].value;

        let errors = [];

        if (!isNotEmpty(name))
            errors.push('El nombre es obligatorio.');
        else if (!isMinLength(name, 3))
            errors.push('El nombre debe tener al menos 3 caracteres.');

        if (!isNotEmpty(email))
            errors.push('El correo electrónico es obligatorio.');
        else if (!isValidEmail(email))
            errors.push('El correo electrónico no es válido.');

        if (!isNotEmpty(password))
            errors.push('La contraseña es obligatoria.');
        else if (!isMinLength(password, 6))
            errors.push('La contraseña debe tener al menos 6 caracteres.');

        if (!isNotEmpty(confirmPassword))
            errors.push('La confirmación de la contraseña es obligatoria.');
        else if (!areEqual(password, confirmPassword))
            errors.push('Las contraseñas no coinciden.');

        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            return;
        }
        */

        sendForm(form, function (response){

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