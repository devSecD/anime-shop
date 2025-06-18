import { isNotEmpty, isMinLength, isValidEmail, areEqual } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';
import { startButtonLoader, stopButtonLoader } from '../../../assets/js/components/formLoader.js';

export function initForgotPasswordForm() {
    const form = document.getElementById("forgotPasswordForm");
    const submitBtn = document.getElementById("forgotPasswordSubmitBtn");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = form.elements['email'].value;

        let errors = [];

        if (!isNotEmpty(email))
            errors.push('El correo electrónico es obligatorio.');
        else if (!isValidEmail(email))
            errors.push('El correo electrónico no es válido.');

        if (errors.length > 0) {
            showToast(errors.join('\n'), 'error');
            return;
        }

        startButtonLoader(submitBtn, "Enviando...");

        try {
            await sendForm(form, function (response){
                if (!response.success) {
                    showToast(response.message, 'error');
                }

                if (response.success) {
                    showToast(response.message, 'success');
                }
            });
        } catch (e) {
            console.log(e);
        } finally {
            stopButtonLoader(submitBtn);
        }

    });

}