<?php
namespace Controllers\User;

use Core\Controller;
use Models\Order\OrderRepository;
use Models\Order\OrderModel;
use App\Helpers\SessionHelper;

class OrderDetailController extends Controller
{
    private OrderRepository $orderRepository;
    private $db;

    public function __construct()
    {
        $this->db = $this->loadDB();
        $orderModel = new OrderModel($this->db);
        $this->orderRepository = new OrderRepository($orderModel);
    }

    public function index()
    {
        $dataUser = SessionHelper::getUser();
        if (!$dataUser || empty($dataUser['user_id'])) {
            header('Location: /anime-shop/public/user/login');
            exit;
        }

        $orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($orderId < 1) {
            header('Location: /anime-shop/public/user/orders');
            exit;
        }

        $order = $this->orderRepository->getOrderWithItems($orderId);

        // Validación: el pedido pertenece al usuario
        if (!$order || $order['user_id'] !== $dataUser['user_id']) {
            header('Location: /anime-shop/public/user/orders');
            exit;
        }

        $content = __DIR__ . '/../../view/user/orderDetail.php';
        $title = "Detalle de pedido #{$order['order_id']}";
        $pageName = 'order-detail';
        $assets = ['orderDetail', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';
    }
}
