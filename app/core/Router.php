<?php
# Sistema de rutas personalizado (si applica)

namespace Core;

class Router
{
    public function handleRequest()
    {

        // Cargar rutas personalizadas
        $customRoutes = require_once __DIR__ . '/../config/routes.php';

        // Obtener URI limpia
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $scriptName = dirname($_SERVER['SCRIPT_NAME']);

        // Elimionar el path base (/anime-shop/public)
        if (str_starts_with($requestUri, $scriptName)) {
            $requestUri = substr($requestUri, strlen($scriptName));
        }

        $url = trim($requestUri, '/');

        // Verificar si hay ruta personalizada
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


        // Obtener la URL limpia
        $url = $_GET['url'] ?? '';
        $url = trim($url, '/');
        $segments = $url !== '' ? explode('/', $url) : [];

        $controller = !empty($segments[0]) ? $segments[0] : 'home';
        $action = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        // Convertir a ruta de clase
        $controllerClass = 'Controllers\\' . ucfirst($controller) . '\\' . ucfirst($action) . 'Controller';

        try {
            if(class_exists($controllerClass)) {
                $instance = new $controllerClass();
                if(method_exists($instance, 'index')) {
                    call_user_func_array([$instance, 'index'], $params);
                } else {
                    throw new \Exception("Metodo 'index' no encontrado en $controllerClass");
                }
            } else {
                throw new \Exception("Controlador $controllerClass no encontrado");
            }
        } catch(\Exception $e) {
            // Puedes crear un ErrorController para manejar esto
            http_response_code(404);
            echo "Error: " .$e->getMessage();
        }
    }
}