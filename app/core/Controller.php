<?php
# Clase base para todos los controladores
namespace Core;
class Controller
{
    protected function render($view, $data = [])
    {
        // Extraer variables
        extract($data);

        // Convertir 'product/list' en ruta real
        $viewPath = APP_PATH . '/view/' . str_replace('.', '/', $view) . '.php';

        if(file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("Vista no encontrada: $viewPath");
        }
    }

    protected function loadDB()
    {
        $config = require APP_PATH . '/config/database.php';

        return new \PDO(
            $config['dsn'], 
            $config['username'], 
            $config['password'], 
            $config['options']
        );
    }
}