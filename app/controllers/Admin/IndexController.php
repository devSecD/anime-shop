<?php
namespace Controllers\Admin;

use Core\Controller;
use App\Middleware\AdminMiddleware;
use Models\Newsletter\NewsletterRepository;
use Models\Order\OrderModel;
use Models\Order\OrderRepository;
use Models\Product\ProductRepository;

class IndexController extends Controller 
{
    private AdminMiddleware $auth;
    private $db;
    public function __construct() 
    {
        $this->auth = new AdminMiddleware();
        $this->db = $this->loadDB();
    }
    public function index(): void 
    {
        // Verificar permisos admin
        $this->auth->handle();

        $productRepo = new ProductRepository($this->db);
        $totalProducts = $productRepo->getTotalProductsCount();
        $recentProducts = $productRepo->getRecentProducts(7);

        $orderModel = new OrderModel($this->db);
        $orderRepo = new OrderRepository($orderModel);
        $totalOrders = $orderRepo->getTotalOrdersCount();
        $pendingOrders = $orderRepo->getPendingOrdersCount();

        $newsletterRepo = new NewsletterRepository($this->db);
        $totalSubscribers = $newsletterRepo->getSubscribersCount();

        $html_head = __DIR__ . '/../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../view/admin/components/sidebar.php';

        $title = 'Dashboard Admin | Anime Shop';
        $page = 'panel_admin_main';
        $assets = ['form'];

        include __DIR__ . '/../../view/admin/index.php';
    }
}