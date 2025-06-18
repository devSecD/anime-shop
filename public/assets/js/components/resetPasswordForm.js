import { isNotEmpty, isMinLength, isValidEmail, areEqual } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';

export function initResetPasswordForm() {

    const form = document.getElementById("resetPasswordForm");
    form.addEventListener("submit", function (e){
        e.preventDefault();

        const password = form.elements['password'].value;
        const confirmPassword = form.elements['confirm_password'].value;

        let errors = [];

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