import { initFileNameDisplay } from '../../../assets/js/ui/fileNameDisplay.js';
import { 
    isNotEmpty,
    isValidEmail,
    mustBePositiveInt,
    mustBeOptionalBoolean, 
    isValidTimezone
} from '../../../assets/js/util/validation.js';

import { sendForm } from "../../../assets/js/ajax/sendForm.js";
import { showToast } from '../../../assets/js/components/alertToast.js';
import { startButtonLoader, stopButtonLoader } from '../../../assets/js/components/formLoader.js';

export function initUpdateConfigurationForm() {

    initFileNameDisplay();
    const form = document.getElementById("updateConfigurationForm");
    const submitBtn = form.querySelector("#btn-add-setting");

    form.addEventListener("submit", function (e) {
            e.preventDefault();

            if (submitBtn.disabled) return;

            let errors = [];
            const settingsInputs = form.querySelectorAll("input[name^='settings']");

            settingsInputs.forEach(input => {
                const key = input.name.replace(/^settings\[(.+)\]$/, '$1');
                const value = input.type === 'checkbox' ? input.checked : input.value.trim();

                switch (key) {
                    case 'site_name':
                        if (!isNotEmpty(value))
                            errors.push('El nombre del sitio es obligatorio.');
                        break;

                    case 'contact_email':
                        if (!isValidEmail(value))
                            errors.push('El correo de contacto no es válido.');
                        break;

                    case 'items_per_page':
                        if (!mustBePositiveInt(value))
                            errors.push('Ítems por página debe ser un número entero positivo.');
                        break;

                    case 'timezone':
                        if (!isValidTimezone(value))
                            errors.push('La zona horaria no es válida.');
                        break;

                    case 'maintenance_mode':
                        const boolError = mustBeOptionalBoolean(value, 'modo mantenimiento');
                        if (boolError) errors.push(boolError);
                        break;

                    default:
                        if (!isNotEmpty(value))
                            errors.push(`El campo "${key}" es obligatorio.`);
                }
            });

            if (errors.length > 0) {
                showToast(errors.join('\n'), 'error');
                return;
            }

            startButtonLoader(submitBtn, "Guardando...");

            sendForm(form, function (response) {
                stopButtonLoader(submitBtn);

                if (!response.success) {
                    showToast(response.message || 'Error al guardar la configuración.', 'error');
                    return;
                }

                showToast(response.message || 'Configuración guardada exitosamente.', 'success');

                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2500);
                }
            });
    });

}