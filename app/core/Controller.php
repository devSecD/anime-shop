<?php
# Clase base para todos los controladores
namespace Core;
class Controller
{
    protected function render($view, $data = [])
    {
        // Extraer variables
        extract($data);

        // Construye la ruta completa del archivo de la vista a partir del nombre proporcionado
        // Ejemplo: 'product.list' → '/ruta_proyecto/app/view/product/list.php'
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