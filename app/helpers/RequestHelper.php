<?php

namespace App\Helpers;

class RequestHelper
{
    /**
     * Obtener un solo parámetro del query string ($_GET)
     *
     * @param string $key     Clave del parámetro
     * @param mixed  $default Valor por defecto si no está presente
     * @return mixed
     */
    public static function getQueryParam(string $key, $default = null) {
        return $_GET[$key] ?? $default;
    }

    public static function getQueryParams(array $keysWithDefaults): array
    {
        $result = [];
        foreach ($keysWithDefaults as $key => $default) {
            $result[$key] = $_GET[$key] ?? $default;
        }
        return $result;
    }
}