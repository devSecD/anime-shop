<?php

namespace App\Helpers;

class RequestHelper
{
    /**
     * Valida que todos los parámetros GET obligatorios estén presentes.
     *
     * @param array $requiredKeys Lista de parámetros GET obligatorios.
     * @param bool $exitOnError Si es true, termina la ejecución en caso de error.
     * @return array Datos filtrados si todo está OK.
     */
    public static function requireGetParams(array $requiredKeys, bool $exitOnError = true): array
    {
        $data = [];

        foreach ($requiredKeys as $key) {
            if (!isset($_GET[$key]) || trim($_GET[$key]) === '') {
                if ($exitOnError) {
                    // Respuesta estandarizada: JSON para API, o HTML para web
                    if (self::isJsonExpected()) { // si espera json
                        header('Content-Type: application/json', true, 400);
                        echo json_encode([
                            'status'  => 'error',
                            'message' => "Falta el parámetro obligatorio: $key"
                        ]);
                    } else { // si no espera html
                        http_response_code(400);
                        echo "<h1>Error 400</h1><p>Falta el parámetro obligatorio: <strong>$key</strong></p>";
                    }
                    exit;
                } else {
                    throw new \InvalidArgumentException("Falta el parámetro obligatorio: $key");
                }
            }

            $data[$key] = htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8');
        }

        return $data;
    }

     // Detecta si el cliente espera JSON (API).
    private static function isJsonExpected(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }

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