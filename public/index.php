<?php
# Punto de entrada de la aplicacion

// Mostrar errores solo en desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Rutas absolutas
define('ROOT', dirname(__DIR__));
define('APP_PATH', ROOT . '/app');

// Cargar Autoload
require_once APP_PATH . '/core/Autoloader.php';
\Core\Autoloader::register();

// Cargar archivo de configuracion (si es necesario)
require_once APP_PATH . '/config/app.php';

// Iniciar sesion si es necesario
session_start();

// Inicializar la aplicacion
use Core\App;

$app = new App();
$app->run();