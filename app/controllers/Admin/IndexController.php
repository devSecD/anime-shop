<?php
namespace Controllers\Admin;

use Core\Controller;
use App\Middleware\AdminMiddleware;
class IndexController extends Controller 
{
    private AdminMiddleware $auth;

    public function __construct() 
    {
        $this->auth = new AdminMiddleware();
    }
    public function index(): void 
    {
        // Verificar permisos admin
        $this->auth->handle();

        $html_head = __DIR__ . '/../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../view/admin/components/sidebar.php';

        $title = 'Dashboard Admin | Anime Shop';
        $page = 'panel_admin_main';
        $assets = ['form'];

        include __DIR__ . '/../../view/admin/index.php';
    }
}