import { isNotEmpty, isMinLength, isValidEmail, isTextLengthBetween } from '../../../assets/js/util/validation.js';
import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { updateFormMessage } from '../../../assets/js/ui/formMessageBox.js';
import { RECAPTCHA_SITE_KEY } from '../core/recaptchaConfig.js';
import { loadRecaptchaScript } from '../core/loadRecaptcha.js';

export function initContactForm() {

    const form = document.getElementById("contactForm");

    form.addEventListener("submit", async function (e){

        e.preventDefault();

        const name = form.elements['name'].value;
        const email = form.elements['email'].value;
        const subject = form.elements['subject'].value;
        const message = form.elements['message'].value;
        const website = form.elements['website'].value;

        let errors = [];

        if (!isNotEmpty(name))
            errors.push('El nombre es obligatorio.');
        else if (!isMinLength(name, 3))
            errors.push('El nombre debe tener al menos 3 caracteres.');
        if (!isNotEmpty(email))
            errors.push('El correo electrónico es obligatorio.');
        else if (!isValidEmail(email))
            errors.push('El correo electrónico no es válido.');

        if (!isNotEmpty(subject))
            errors.push('El asunto es obligatorio.');
        else if (!isTextLengthBetween(subject, 3, 100))
            errors.push('El asunto debe tener entre 3 y 100 caracteres.');

        if (!isNotEmpty(message))
            errors.push('El mensaje es obligatorio.');
        else if (!isTextLengthBetween(message, 10, 1000))
            errors.push('El mensaje debe tener entre 10 y 1000 caracteres.');

        if (website.trim() !== '') {
            return updateFormMessage("contactForm", "Se detectó actividad sospechosa.", false);
        }

        if (errors.length > 0) {
            updateFormMessage("contactForm", errors.join('<br>'), false);
            return;
        }

        try {
            // Cargar reCAPTCHA
            await loadRecaptchaScript(RECAPTCHA_SITE_KEY);
            grecaptcha.ready(async () => {

                const token = await grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'contact_form' });

                // Adjuntar token al formulario
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = 'recaptcha_token';
                tokenInput.value = token;
                form.appendChild(tokenInput);

                // Enviar formulario con AJAX
                sendForm(form, function (response) {
                    if (!response.success) {
                        // Backend puede mandar errores detallados
                        if (response.errors) {
                            const fieldErrors = Object.values(response.errors).join('<br>');
                            updateFormMessage("contactForm", fieldErrors, false);
                        } else {
                            updateFormMessage("contactForm", response.message, false);
                        }
                        return;
                    }

                    // Éxito
                    updateFormMessage("contactForm", response.message, true);
                    form.reset();
                });

            });
        } catch (err) {
            console.error(err);
            updateFormMessage("contactForm", "Error al cargar reCAPTCHA. Intenta de nuevo.", false);
        }
    });
}