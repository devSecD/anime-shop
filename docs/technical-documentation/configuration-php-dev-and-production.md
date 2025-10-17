# Configuración de PHP para desarrollo y producción

Este documento explica cómo manejar la visualización y registro de errores en PHP de manera segura, diferenciando entre **entorno de desarrollo** y **producción**.

---

## Desarrollo

En desarrollo queremos ver todos los errores para poder depurar rápidamente.

```php
// Mostrar errores en pantalla (útil para desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

- `ini_set('display_errors', 1)` → muestra los errores directamente en el navegador.
- `error_reporting(E_ALL)` → muestra **todos** los errores, advertencias y avisos.

---

## Producción

En producción no queremos mostrar errores al usuario, pero sí registrarlos para que el equipo técnico pueda revisarlos.

```php
// Producción: no mostrar errores al usuario, sí registrarlos en el log de Apache
ini_set('display_errors', 0);  // Oculta los errores en el navegador
ini_set('log_errors', 1);      // Activa el registro de errores
error_reporting(E_ALL);        // Registrar todos los errores
// No se especifica error_log, se usará el log por defecto de Apache
```

- `ini_set('display_errors', 0)` → oculta los errores al usuario.
- `ini_set('log_errors', 1)` → habilita el registro de errores.
- `error_reporting(E_ALL)` → registra todos los errores.
- Al no especificar `error_log`, PHP usa el archivo de log por defecto de Apache, que normalmente es:
  - Linux (Debian/Ubuntu): `/var/log/apache2/error.log`
  - Linux (CentOS/RedHat): `/var/log/httpd/error_log`
  - Windows (XAMPP/WAMP/Laragon): `logs\php_error.log` dentro de la instalación de Apache.

---

## Buenas prácticas

1. **Separar entornos**: usar variables de entorno (`APP_ENV`) para diferenciar desarrollo de producción.
2. **No mostrar errores en producción**: evita que usuarios vean información sensible.
3. **Registrar errores**: siempre guardar los errores en un log seguro para análisis posterior.
4. **Carpeta de logs protegida**: si decides usar un log personalizado, ubicarlo fuera de la carpeta `public/`.

---

Con esta configuración, tu proyecto PHP seguirá buenas prácticas de manejo de errores, mostrando toda la información útil durante desarrollo y protegiendo la información sensible en producción.