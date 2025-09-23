<?php
namespace Controllers\Admin\Order;

use Core\Controller;
use Models\Order\OrderModel;
use Models\Order\OrderRepository;
use App\Middleware\AdminMiddleware;
use Core\Paginator;

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

        $status = $_GET['status'] ?? null;

        // total de órdenes (el repo ya decide si cuenta todas o solo pending)
        $totalOrders = $this->orderRepo->countOrders($status);

        // instanciamos el paginador
        $paginator = new Paginator($totalOrders, 15);

        // obtenemos las órdenes paginadas
        $orders = $this->orderRepo->getPaginatedOrders(
            $paginator->getLimit(),
            $paginator->getOffset(),
            $status // se lo pasamos si viene
        );

        // armamos datos de paginación para la vista
        $pagination = [
            'currentPage' => $paginator->getCurrentPage(),
            'totalPages'  => $paginator->getTotalPages(),
            'hasPrev'     => $paginator->hasPrev(),
            'hasNext'     => $paginator->hasNext()
        ];

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';
        $modalConfirmDelete = __DIR__ . '/../../../view/admin/components/modal-confirm-delete.php';

        $title = 'Listado de órdenes';
        $page = 'admin_orders_list';

        extract(compact('orders', 'pagination', 'status', 'title', 'page'));
        include __DIR__ . '/../../../view/admin/orders/list.php';
    }
}