<?php
# Punto de entrada de la aplicacion

// Mostrar errores solo en desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Rutas absolutas
define('ROOT', dirname(__DIR__)); // raíz del proyecto
define('APP_PATH', ROOT . '/app');

define('BASE_PATH', dirname(__DIR__)); // base path para rutas

// ** Cargar Autoload de Composer primero **
require_once ROOT . '/vendor/autoload.php';

// Cargar Autoload custom
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