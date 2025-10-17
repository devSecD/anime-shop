# Documentación de Toasts y Manejo de Errores en el Carrito - Anime Shop

## 1. Introducción
En Anime Shop, los mensajes de notificación (toasts) se utilizan para informar al usuario sobre acciones exitosas, errores de validación o excepciones en el carrito de compras. La lógica combina **backend PHP**, **JavaScript frontend** y **CSS de estilo y animación**.

---

## 2. Backend: CartController.php
El controlador `CartController` devuelve respuestas JSON con la siguiente estructura:

```json
{
  "success": boolean,
  "message": string,
  "error_type": "validation" | "exception" | "unknown",
  "count": int,        // opcional
  "total": float       // opcional
}
```

- `error_type` indica el tipo de error y se mapea a un tipo de toast en frontend.
- `message` es el texto que se mostrará al usuario.

Ejemplo de error de validación:
```json
{
  "success": false,
  "error_type": "validation",
  "message": "No puedes agregar más del mismo producto"
}
```

---

## 3. Frontend JavaScript

### 3.1 handleCartError.js
Se encarga de recibir los errores del backend y mostrarlos usando `showToast`.

```js
import { showToast } from '../components/alertToast.js';

/**
 * Muestra un mensaje de error o advertencia del carrito.
 * @param {string} message - Mensaje descriptivo del error.
 * @param {('validation'|'exception'|'unknown')} [type='error'] - Tipo de error.
 */
export function handleCartError(message, type = 'error') {
    const types = {
        validation: 'warning',
        exception: 'error',
        unknown: 'error'
    };
    const toastType = types[type] || 'error';
    showToast(message || 'Ha ocurrido un error', toastType);
}
```

- `types` mapea `error_type` del backend a un tipo de toast compatible con CSS.
- `showToast` crea un toast visualmente y lo agrega al DOM.

### 3.2 showToast.js
Crea y muestra el toast en pantalla con animación y duración determinada.

```js
export function showToast(message = '', type = 'success', duration = 3000) {
    if (!message || String(message).trim() === '') return;
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 500);
    }, duration);
}
```

- `type` determina la clase CSS: `toast-success`, `toast-error`, `toast-warning`.
- `duration` controla el tiempo que el toast permanece visible.

---

## 4. CSS de los Toasts

```css
.toast {
    position: fixed;
    bottom: 2rem;
    left: 50%;
    background-color: var(--background-color-secondary);
    color: var(--text-color);
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    font-family: var(--main-font);
    font-size: var(--font-size-small);
    z-index: 9999;
    opacity: 0;
    animation: fadeInOut 4s ease forwards;
    max-width: 300px;
}

.toast-success { background-color: var(--primary-color); color: var(--text-color); }
.toast-error { background-color: var(--accent-color); color: var(--text-color); }
.toast-warning { background-color: orange; color: var(--text-color); } /* falta agregar este estilo al css de la version 1.0 del proyecto */
.toast.hide { opacity: 0; transform: translateX(-50%) translateY(20px); }

@keyframes fadeInOut {
    0% { opacity: 0; transform: translateY(20px); }
    10%, 90% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(20px); }
}
```

- `.toast` → estilo base y animación.  
- `.toast-success`, `.toast-error`, `.toast-warning` → colores según tipo de mensaje.  
- `.hide` → oculta el toast antes de eliminarlo del DOM.

---

## 5. Flujo completo del error en el carrito

1. Usuario hace clic en agregar, actualizar o eliminar producto.  
2. JS envía la petición AJAX a `CartController`.  
3. Backend devuelve JSON con `success`, `message` y opcionalmente `error_type`.  
4. JS verifica `res.success`:
   - `true` → actualiza carrito, contador y total, muestra toast de éxito.  
   - `false` → llama a `handleCartError(res.message, res.error_type)`.
5. `handleCartError` mapea `error_type` a clase CSS (`warning` o `error`).  
6. `showToast` genera visualmente el toast en el DOM y se anima con `fadeInOut`.  
7. Toast desaparece automáticamente tras `duration` milisegundos.

---

## 6. Conclusión
El uso de `handleCartError` y `showToast` permite:
- Centralizar el manejo de errores del carrito.  
- Mostrar mensajes claros y consistentes al usuario.  
- Diferenciar errores de validación (warning) de errores del sistema (error).  
- Integrar backend, JS y CSS de forma escalable y mantenible.