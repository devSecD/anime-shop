<?php

namespace App\Helpers;

class UrlHelper
{
    public static function base_url(string $path = ''): string 
    {
        // Cargar configuración desde archivo separado
        $config = require __DIR__ . '/../config/url.config.php';
        // rtrim() elimina los caracteres en blanco (u otros que tú especifiques) del final de una cadena, es decir, a la derecha.
        return rtrim($config['base_url'], '/') . '/' . ltrim($path, '/');
    }
}