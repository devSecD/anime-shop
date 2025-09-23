<?php
namespace Controllers\Admin\Order;

use Core\Controller;
use Models\Order\OrderRepository;
use Models\Order\OrderModel;

class DetailController extends Controller
{
    private OrderRepository $repo;
    private $db;
    public function __construct()
    {
        $this->db = $this->loadDB();
        $model = new OrderModel($this->db);
        $this->repo = new OrderRepository($model);
    }

    public function index(): void
    {
        if (!isset($_GET['order_id'])) {
            header('Location: /anime-shop/public/admin/orders');
            exit;
        }

        $orderId = (int) $_GET['order_id'];
        $order = $this->repo->getOrderWithItems($orderId);

        if (!$order) {
            header('Location: /anime-shop/public/admin/orders');
            exit;
        }

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';

        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';

        $title = "Detalle de orden # " . $order['order_id'];
        $page = 'order_detail';
        include __DIR__ . '/../../../view/admin/orders/detail.php';
    }
}
