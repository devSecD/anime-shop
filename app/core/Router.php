<?php
# Sistema de rutas personalizado (si applica)

namespace Core;

use App\Helpers\RequestHelper;
use App\Helpers\StringHelper;

class Router
{
    public function handleRequest()
    {
        // Obtener URI limpia
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);

        // Elimionar el path base (/anime-shop/public)
        if (str_starts_with($requestUri, $scriptName)) {
            $requestUri = substr($requestUri, strlen($scriptName));
        }

        $url = trim($requestUri, '/');

        // -------------------------
        // 📌 Rutas Personalizadas
        // -------------------------
        $customRoutes = require_once __DIR__ . '/../config/routes.php';

        if (isset($customRoutes[$url])) {
            $route = $customRoutes[$url];
            $controllerClass = 'Controllers\\' . $route['controller'];
            $action = $route['action'];

            $controllerFile = __DIR__ . '/../' . str_replace('\\', '/', $controllerClass) . '.php';

            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $instance = new $controllerClass();

                if (method_exists($instance, $action)) {
                    $instance->$action();
                    return;
                } else {
                    throw new \Exception("Metodo '$action' no encontrado en $controllerClass");
                }
            } else {
                throw new \Exception("Archivo del controlador no encontrado: $controllerFile");
            }
        }


        // -------------------------
        // 📌 Rutas Dinámicas convencionales (/controlador/accion)
        // -------------------------
        // $url = $_GET['url'] ?? '';
        $url = RequestHelper::getQueryParam('url', '');
        $url = trim($url, '/');
        $segments = $url !== '' ? explode('/', $url) : [];

        $controller = !empty($segments[0]) ? StringHelper::toPascalCase($segments[0]) : 'home';
        $action = $segments[1] ? StringHelper::toPascalCase($segments[1]) : 'index';
        $params = array_slice($segments, 2);

        // Convertir a ruta de clase
        $controllerClass = 'Controllers\\' . ucfirst($controller) . '\\' . ucfirst($action) . 'Controller';

        try {
            if(class_exists($controllerClass)) {
                $instance = new $controllerClass();

                $method = $params[0] ?? 'index';
                $methodParams = array_slice($params, 1);

                if(method_exists($instance,$method)) {
                    call_user_func_array([$instance, $method], $methodParams);
                } else {
                    throw new \Exception("Metodo 'index' no encontrado en $controllerClass");
                }
            } else {
                throw new \Exception("Controlador $controllerClass no encontrado");
            }
        } catch(\Exception $e) {
            http_response_code(404);
            echo "Error: " .$e->getMessage();
        }
    }
}