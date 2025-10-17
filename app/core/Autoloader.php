<?php
namespace Core;

class Autoloader
{

    /**
     * Registra el autoloader de clases de la aplicación.
     *
     * Este método se encarga de registrar el método estático `autoload` de esta clase
     * como el autoloader principal de PHP mediante `spl_autoload_register()`. 
     * Gracias a esto, cada vez que se intente instanciar una clase que aún no se haya
     * cargado, PHP llamará automáticamente a `Autoloader::autoload($className)` para
     * incluir el archivo correspondiente sin necesidad de `require` o `include` manual.
     *
     * Uso típico:
     * Autoloader::register();
     *
     * @return void
     */
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Autocarga una clase de la aplicación según su namespace.
     *
     * Este método se encarga de localizar e incluir automáticamente el archivo PHP
     * correspondiente a una clase de la aplicación cuando se intenta instanciar
     * y aún no ha sido cargada. Está pensado para los namespaces principales de la app:
     * - App\
     * - Core\
     * - Models\
     * - Controllers\
     *
     * Funcionamiento:
     * 1. Verifica si el nombre de la clase comienza con uno de los namespaces soportados.
     * 2. Si comienza con "App\", elimina este prefijo para calcular la ruta relativa.
     * 3. Reemplaza los backslashes '\' por separadores de directorio según el sistema.
     * 4. Construye la ruta completa hacia el archivo en el directorio /app.
     * 5. Si el archivo existe, lo incluye mediante `require_once`.
     * 6. Si el archivo no existe, lanza una excepción con información detallada.
     *
     * Las clases fuera de estos namespaces no son cargadas por este autoloader,
     * pudiendo ser gestionadas por Composer u otros autoloaders.
     *
     * @param string $class Nombre completo de la clase a cargar (con namespace)
     * @return void
     * @throws \Exception Si el archivo de la clase no existe
     */
    public static function autoload($class)
    {
        // Elimina el namespace raíz "App\" para resolver la ruta relativa desde /app
        if (strpos($class, 'App\\') === 0 || 
            strpos($class, 'Core\\') === 0 || 
            strpos($class, 'Models\\') === 0 || 
            strpos($class, 'Controllers\\') === 0) {

            // Si empieza con App\, quitarlo para resolver desde /app
            if (strpos($class, 'App\\') === 0) {
                $class = substr($class, 4); // Quita 'App\'
            }

            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . $class . '.php';

            if (file_exists($file)) {
                require_once $file;
            } else {
                throw new \Exception("No se pudo cargar la clase: $class (ruta: $file)");
            }
        }
        // Si la clase no es de esos namespaces, no hacemos nada:
        // Composer (vendor/autoload.php) se encargará.
    }
}