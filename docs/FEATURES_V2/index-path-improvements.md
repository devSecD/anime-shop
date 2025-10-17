# Mejora de rutas absolutas en `public\index.php`

Estas líneas del archivo `public\index.php`, que es el punto de entrada de la aplicación **Anime Shop**, se deben mejorar para la versión 2.

## Código original

```php
// Rutas absolutas
define('ROOT', dirname(__DIR__)); // raíz del proyecto
define('APP_PATH', ROOT . '/app');

define('BASE_PATH', dirname(__DIR__)); // base path para rutas
```

## Código recomendado para versión 2

```php
// Ruta raíz del proyecto (directorio padre de 'public')
define('ROOT', dirname(__DIR__));

// Ruta de la carpeta 'app'
define('APP_PATH', ROOT . '/app');

// (Opcional) Si quieres mantener otra referencia semántica, puedes reutilizar ROOT
// define('BASE_PATH', ROOT);  // Solo si realmente necesitas un alias
```

### Explicación

1. `ROOT` define la ruta del directorio raíz del proyecto, que normalmente es el padre del directorio `public`.
2. `APP_PATH` apunta directamente a la carpeta `app` dentro del proyecto, donde se encuentran modelos, controladores y vistas.
3. `BASE_PATH` es opcional. Solo se mantiene si se necesita un alias semántico adicional; de lo contrario, `ROOT` es suficiente.

Esta mejora ayuda a que el código sea más claro y mantenible, evitando confusiones con rutas duplicadas o ambiguas.