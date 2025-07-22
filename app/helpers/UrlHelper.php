<?php

namespace App\Helpers;

class UrlHelper
{
    public static function base_url(string $path = ''): string {
        // Cargar configuración desde archivo separado
        $config = require __DIR__ . '/../config/url.config.php';
        return rtrim($config['base_url'], '/') . '/' . ltrim($path, '/');
    }
}