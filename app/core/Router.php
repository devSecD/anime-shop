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
        // 📌 Rutas Dinámicas convencionales y admin
        // -------------------------

        $url = RequestHelper::getQueryParam('url', '');
        $url = trim($url, '/');
        $segments = $url !== '' ? explode('/', $url) : [];

        try {
            if (!empty($segments) && strtolower($segments[0]) === 'admin') {
                // 👑 Rutas tipo /admin/module/action/method
                // Ej: /admin/products/create/process

                $area = ucfirst(array_shift($segments)); // admin
                $module = !empty($segments) ? ucfirst(array_shift($segments)) : 'Home';
                $action = !empty($segments) ? ucfirst(array_shift($segments)) : 'Index';

                $controllerClass = "Controllers\\$area\\$module\\{$action}Controller";
                $controllerFile = __DIR__ . '/../' . str_replace('\\', '/', $controllerClass) . '.php';

                if (!file_exists($controllerFile)) {
                    throw new \Exception("Archivo del controlador no encontrado: $controllerFile");
                }

                require_once $controllerFile;
                $instance = new $controllerClass();

                // $method = !empty($segments) ? array_shift($segments) : 'index';
                $method = 'index';
                $params = [];

                if (!empty($segments)) {
                    $nextSegment = $segments[0];

                    if (is_numeric($nextSegment)) {
                        // Es un ID: método index con parámetro
                        $method = 'index';
                        $params = $segments;
                    } else {
                        // Es método (process, store, delete...)
                        $method = array_shift($segments);
                        $params = $segments;
                    }
                }

                if (!method_exists($instance, $method)) {
                    throw new \Exception("Metodo '$method' no encontrado en $controllerClass");
                }

                call_user_func_array([$instance, $method], $segments);
            } else {
                // 🧍 Rutas normales: /controlador/accion
                $controller = !empty($segments[0]) ? StringHelper::toPascalCase($segments[0]) : 'Home';
                $action = isset($segments[1]) ? StringHelper::toPascalCase($segments[1]) : 'Index';
                $params = array_slice($segments, 2);

                $controllerClass = "Controllers\\$controller\\{$action}Controller";
                $controllerFile = __DIR__ . '/../' . str_replace('\\', '/', $controllerClass) . '.php';

                if (!file_exists($controllerFile)) {
                    throw new \Exception("Archivo del controlador no encontrado: $controllerFile");
                }

                require_once $controllerFile;
                $instance = new $controllerClass();

                $method = !empty($params) ? array_shift($params) : 'index';

                if (!method_exists($instance, $method)) {
                    throw new \Exception("Metodo '$method' no encontrado en $controllerClass");
                }

                call_user_func_array([$instance, $method], $params);
            }
        } catch (\Exception $e) {
            http_response_code(404);
            echo "Error (desde el catch): " . $e->getMessage();
        }

    }
}