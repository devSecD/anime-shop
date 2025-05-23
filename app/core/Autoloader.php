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
        // Reemplazar namespace por ruta relativa
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

        // Asumimos que todas las clases estan dentro de la carpeta /app
        $file = __DIR__ . '/../' . $class . '.php';

        if(file_exists($file)) {
            require_once $file;
        } else {
            // Opcional: lanzar excepcion para facilitar debug
            throw new \Exception("No se pudo cargar la clase: $class");
        }
    }
}