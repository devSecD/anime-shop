<?php
# Sistema de rutas personalizado (si applica)

namespace Core;

class Router
{
    public function handleRequest()
    {
        // Obtener la URL limpia
        $url = $_GET['url'] ?? '';
        $url = trim($url, '/');
        $segments = explode('/', $url);

        // Definir valores por defecto
        $controller = $segments[0] ?? 'home';
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