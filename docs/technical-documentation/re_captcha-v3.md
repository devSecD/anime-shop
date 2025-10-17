# 🔒 Implementación de reCAPTCHA en Formulario de Contacto

## 📘 Descripción general

El formulario de contacto de *Anime Shop* implementa **Google reCAPTCHA
v3** como medida de protección adicional frente al spam y el envío
automatizado de formularios.\
Esta versión no requiere interacción del usuario, ya que genera un token
basado en comportamiento y contexto del usuario.

------------------------------------------------------------------------

## 💻 Implementación en el código JavaScript

El proceso de integración se realiza de forma asíncrona dentro del flujo
de envío del formulario:

``` js
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
                if (response.errors) {
                    const fieldErrors = Object.values(response.errors).join('<br>');
                    updateFormMessage("contactForm", fieldErrors, false);
                } else {
                    updateFormMessage("contactForm", response.message, false);
                }
                return;
            }

            updateFormMessage("contactForm", response.message, true);
            form.reset();
        });

    });
} catch (err) {
    console.error(err);
    updateFormMessage("contactForm", "Error al cargar reCAPTCHA. Intenta de nuevo.", false);
}
```

------------------------------------------------------------------------

## ⚙️ Flujo técnico paso a paso

1.  **Carga del script de reCAPTCHA**
    -   Se ejecuta `loadRecaptchaScript(RECAPTCHA_SITE_KEY)` para cargar
        el script oficial de Google.
2.  **Inicialización asíncrona**
    -   `grecaptcha.ready()` asegura que el script esté completamente
        cargado antes de su ejecución.
3.  **Obtención del token**
    -   `grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'contact_form' })`
        genera un token único asociado a la acción del formulario.
4.  **Inserción del token en el formulario**
    -   Se crea dinámicamente un campo oculto
        `input[name="recaptcha_token"]` con el valor del token generado.
5.  **Envío mediante AJAX**
    -   Se llama a `sendForm()` para enviar los datos al servidor sin
        recargar la página.
    -   Si la respuesta contiene errores, se muestran en pantalla
        mediante `updateFormMessage()`.
6.  **Manejo de errores**
    -   Si el script de reCAPTCHA no puede cargarse, se captura la
        excepción (`catch`) y se notifica al usuario con un mensaje de
        error.

------------------------------------------------------------------------

## ✅ Beneficios del uso de reCAPTCHA v3

-   No requiere interacción directa del usuario.\
-   Analiza patrones de comportamiento para distinguir humanos de bots.\
-   Mejora la seguridad sin afectar la usabilidad.\
-   Compatible con envío asíncrono (AJAX).

------------------------------------------------------------------------

## 🚨 Consideraciones

-   El token generado tiene una validez corta (aprox. 2 minutos).\
-   Es necesario **verificar el token en el backend** mediante la API de
    Google para validar la legitimidad del envío.\
-   Debe combinarse con otras medidas de seguridad como el Honeypot y
    validaciones del servidor.

------------------------------------------------------------------------

**Implementación actual:**\
\> Se genera y adjunta dinámicamente un token reCAPTCHA v3 al formulario
de contacto antes de su envío por AJAX. En caso de error, se muestra un
mensaje al usuario y el flujo se detiene.

------------------------------------------------------------------------

# 🧩 Integración de Google reCAPTCHA v3 en Formularios (Anime Shop)

Esta sección documenta la integración de **Google reCAPTCHA v3** en los formularios de la tienda en línea *Anime Shop*, utilizada para prevenir envíos automatizados (bots) y garantizar la validez de las peticiones.

---

## 📘 Descripción General

Google reCAPTCHA v3 analiza el comportamiento del usuario en el sitio para asignar una **puntuación de confianza** sin requerir interacción (como seleccionar imágenes).  
Esto permite detectar tráfico sospechoso sin afectar la experiencia del usuario.

En *Anime Shop*, reCAPTCHA se usa en formularios críticos como el de **contacto**, **registro**, y **newsletter**.

---

## ⚙️ Archivos Involucrados

| Archivo | Descripción |
|----------|-------------|
| `recaptchaConfig.js` | Contiene la clave pública (`RECAPTCHA_SITE_KEY`). |
| `loadRecaptcha.js` | Carga dinámicamente el script oficial de Google reCAPTCHA. |
| `initContactForm.js` | Ejemplo práctico donde se utiliza el reCAPTCHA antes de enviar el formulario. |

---

## 📄 Implementación en Formularios (Ejemplo: `initContactForm.js`)

```javascript
await loadRecaptchaScript(RECAPTCHA_SITE_KEY);

grecaptcha.ready(async () => {
    const token = await grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'contact_form' });

    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = 'recaptcha_token';
    tokenInput.value = token;
    form.appendChild(tokenInput);

    sendForm(form, (response) => {
        if (!response.success) {
            updateFormMessage("contactForm", response.message, false);
            return;
        }
        updateFormMessage("contactForm", response.message, true);
        form.reset();
    });
});
```

---

## 🧠 Flujo de Ejecución

1. Se carga el script de Google reCAPTCHA solo cuando se envía el formulario.  
2. Una vez cargado, se ejecuta `grecaptcha.ready()`.  
3. Google analiza el comportamiento del usuario y genera un **token de validación**.  
4. El token se adjunta al formulario mediante un campo oculto (`recaptcha_token`).  
5. El backend valida ese token llamando a la API de verificación de Google (`https://www.google.com/recaptcha/api/siteverify`).  
6. Si la validación es exitosa, el servidor procesa normalmente la petición.

---

## ✅ Buenas Prácticas

- **Nunca expongas la clave secreta** (`secret key`) en el frontend. Solo la **clave pública** debe ir en el navegador.  
- **Valida siempre el token en el servidor** antes de aceptar la petición.  
- Usa un **umbral de puntuación** (score threshold) en el backend para decidir si aceptar o rechazar solicitudes.  
- Carga el script **solo cuando sea necesario**, para optimizar rendimiento.  
- En formularios con AJAX, asegúrate de **agregar dinámicamente el campo oculto** con el token, como se muestra en el ejemplo.

---

## 🧾 Conclusión

Integrar reCAPTCHA v3 en los formularios de *Anime Shop* permite:
- Reducir envíos automáticos y spam.
- Proteger endpoints sensibles sin afectar la experiencia del usuario.
- Mantener una arquitectura modular y reutilizable, separando configuración, carga y ejecución.