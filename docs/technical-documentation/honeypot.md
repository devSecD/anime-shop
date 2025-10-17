# Honeypot en Anime Shop

## 1. Diferencia entre honeypot a nivel servidor y honeypot a nivel código

### Honeypot a nivel código (form-level / campo "trampa")

-   **Qué es:** un campo de formulario que no es visible para usuarios
    humanos pero sí para bots (ejemplo:
    `<input type="text" name="website">` oculto con CSS).
-   **Dónde vive:** en el *frontend* (vista HTML) y validación en el
    *backend* (controlador).
-   **Datos que da:** detección rápida de bots que rellenan todos los
    inputs.
-   **Sencillez:** fácil de añadir en cualquier form.
-   **Limitaciones:** bots avanzados lo detectan, posibles falsos
    positivos.

### Honeypot a nivel servidor (network/honeyserver / honeytoken)

-   **Qué es:** un servicio o recurso deliberadamente falso o vulnerable
    expuesto en la red o aplicación.
-   **Dónde vive:** a nivel de infraestructura o capa de aplicación
    (endpoints falsos, honeytokens, servicios simulados).
-   **Datos que da:** interacciones, payloads, técnicas de ataque, IPs.
-   **Complejidad:** requiere infraestructura, logging y monitoreo.
-   **Limitaciones:** mantenimiento costoso, riesgos legales/operativos.

------------------------------------------------------------------------

## 2. ¿Para qué sirven ambos?

### Form honeypot (nivel código)

-   Bloquear spam en formularios (contacto, registro, newsletter).
-   Detectar bots simples que rellenan todo.
-   Baja fricción para el usuario (mejor UX que CAPTCHA).
-   Complemento de otras técnicas: CSRF, rate-limiting, etc.

### Server-level honeypot

-   Detectar escaneos y ataques dirigidos.
-   Recolectar técnicas de atacantes para mejorar seguridad.
-   Identificar intentos de explotación y compromisos internos.
-   Monitorear fuga de datos con *honeytokens*.

------------------------------------------------------------------------

## 3. Pros y Contras

  ------------------------------------------------------------------------
  Tipo                                  Pros           Contras
  ------------------------------------- -------------- -------------------
  **Form honeypot**                     Simple,        Ineficaz contra
                                        barato, sin    bots avanzados,
                                        afectar UX,    posibles falsos
                                        bloquea spam   positivos
                                        básico         

  **Server honeypot**                   Gran           Requiere más
                                        visibilidad,   recursos,
                                        detección      mantenimiento,
                                        avanzada,      riesgos legales
                                        análisis       
                                        profundo       
  ------------------------------------------------------------------------

------------------------------------------------------------------------

## 4. Recomendaciones para Anime Shop

1.  **Mantener honeypot en código** y validación en el backend.
2.  **Reducir falsos positivos:**
    -   Nombre de campo dinámico por sesión (`hp_xy83`).
    -   Añadir timestamp (`form_rendered_at`) para validar tiempo
        razonable.
    -   Revisar User-Agent y Referer.
3.  **Accesibilidad:** ocultar con CSS pero también excluir de lectores
    de pantalla (`aria-hidden="true"`, `tabindex="-1"`).
4.  **Registro:** crear tabla `honeypot_logs` para almacenar intentos
    sospechosos.
5.  **Acción escalonada:** primero registrar, después aplicar CAPTCHA o
    bloqueo temporal.

------------------------------------------------------------------------

# 🛡️ Protección Honeypot en Formulario de Contacto

## 📘 Descripción general

La implementación del **Honeypot** en el formulario de contacto del
proyecto *Anime Shop* tiene como objetivo **detectar y bloquear envíos
automáticos (bots)** sin afectar la experiencia del usuario real.\
Se basa en agregar un campo oculto que los usuarios legítimos no
completan, pero que los bots suelen rellenar al intentar enviar el
formulario.

------------------------------------------------------------------------

## 💻 Implementación en el código

### 1. Campo Honeypot en la vista HTML

En la plantilla del formulario de contacto se añadió un campo adicional
invisible al usuario:

``` html
<input type="text" name="website" autocomplete="off" tabindex="-1">
```

-   **`name="website"`**: nombre del campo honeypot.
-   **`autocomplete="off"`**: evita que el navegador sugiera valores.
-   **`tabindex="-1"`**: impide que el campo reciba foco mediante el
    teclado.
-   No se aplica CSS visible, de modo que el campo pasa inadvertido para
    el usuario.

------------------------------------------------------------------------

### 2. Validación del Honeypot en el JavaScript

Antes de enviar los datos al servidor, se ejecuta una validación:

``` js
if (website.trim() !== '') {
    return updateFormMessage("contactForm", "Se detectó actividad sospechosa.", false);
}
```

#### Explicación técnica:

-   **`website`** representa el valor del campo honeypot.
-   Si el campo contiene algún texto (`!== ''`), se considera que el
    envío **proviene de un bot**.
-   Se muestra un mensaje de alerta con `updateFormMessage()` y se
    detiene el envío del formulario (`return`).

------------------------------------------------------------------------

## ⚙️ Flujo de funcionamiento

1.  El usuario (o bot) intenta enviar el formulario.\
2.  El script obtiene el valor del campo `website`.\
3.  Si está vacío → se continúa el flujo normal.\
4.  Si contiene texto → se bloquea el envío y se muestra el mensaje de
    advertencia.

------------------------------------------------------------------------

## ✅ Ventajas del enfoque Honeypot

-   No requiere interacción del usuario.\
-   Compatible con JavaScript y backend.\
-   Ligero y sin dependencias externas.\
-   Complementa otras medidas de seguridad como reCAPTCHA.

------------------------------------------------------------------------

## 🚨 Limitaciones

-   Algunos bots avanzados pueden detectar y evitar llenar campos
    ocultos.\
-   Debe usarse como **capa adicional de seguridad**, no como única
    defensa.

------------------------------------------------------------------------

**Implementación actual:**\
\> Validación básica del campo `website` en el cliente, mostrando
mensaje de actividad sospechosa si se detecta contenido no vacío.