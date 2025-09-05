/**
 * Carga dinámicamente la librería de Google reCAPTCHA v3.
 * @param {string} siteKey - Tu clave pública de reCAPTCHA.
 * @returns {Promise<void>}
 */
export function loadRecaptchaScript(siteKey) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src*="${siteKey}"]`)) {
            // Si ya se cargó, resolvemos inmediatamente
            resolve();
            return;
        }

        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
        script.async = true;
        script.defer = true;
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('No se pudo cargar reCAPTCHA'));
        document.head.appendChild(script);
    });
}
