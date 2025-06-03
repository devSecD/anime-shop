<?php
namespace Core;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload($class)
    {
        // Elimina el namespace raíz "App\" para resolver la ruta relativa desde /app
        if (strpos($class, 'App\\') === 0) {
            $class = substr($class, 4); // Remueve 'App\' del namespace
        }

        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
        } else {
            throw new \Exception("No se pudo cargar la clase: $class (ruta: $file)");
        }
    }
}