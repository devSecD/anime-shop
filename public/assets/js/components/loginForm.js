import { isNotEmpty, isMinLength, isValidEmail, areEqual } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';

export function initLoginForm() {
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", function (e){
        e.preventDefault();

        const email = form.elements['email'].value;
        const password = form.elements['password'].value;

        let errors = [];

        if (!isNotEmpty(email))
            errors.push('El correo electrónico es obligatorio.');
        else if (!isValidEmail(email))
            errors.push('El correo electrónico no es válido.');

        if (!isNotEmpty(password))
            errors.push('La contraseña es obligatoria.');

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