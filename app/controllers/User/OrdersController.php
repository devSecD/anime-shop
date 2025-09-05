<?php
namespace Controllers\User;

use Core\Controller;
use Models\Order\OrderRepository;
use Models\Order\OrderModel;
use App\Helpers\SessionHelper;

class OrdersController extends Controller 
{
    private $db;
    private $orderRepository;

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
            // Redirige si no hay sesión
            header('Location: /anime-shop/public/user/login');
            exit;
        }

        $orders = $this->orderRepository->getUserOrders($dataUser['user_id'], 5);

        $content = __DIR__ . '/../../view/user/orders.php';
        $title = 'Mis pedidos';
        $page = 'orders';

        $assets = ['orders', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';
    }
}