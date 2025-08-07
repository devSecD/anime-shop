<?php
namespace Controllers\Admin\Order;

use Core\Controller;
use Models\Order\OrderModel;
use Models\Order\OrderRepository;
use App\Middleware\AdminMiddleware;

class ListController extends Controller
{
    private OrderRepository $orderRepo;
    private AdminMiddleware $auth;

    public function __construct()
    {
        $db = $this->loadDB();
        $model = new OrderModel($db);
        $this->orderRepo = new OrderRepository($model);
        $this->auth = new AdminMiddleware();
    }

    public function index(): void
    {
        $this->auth->handle();

        $limit = 50;
        $offset = 0;
        $orders = $this->orderRepo->getPaginatedOrders($limit, $offset);

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';
        $modalConfirmDelete = __DIR__ . '/../../../view/admin/components/modal-confirm-delete.php';

        $title = 'Listado de órdenes';
        $page = 'admin_orders_list';

        extract(compact('orders', 'title', 'page'));
        include __DIR__ . '/../../../view/admin/orders/list.php';
    }
}
?>