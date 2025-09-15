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

        // Parámetros de paginación
        $perPage = 6; 
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        $offset = ($currentPage - 1) * $perPage;

        // Obtener pedidos y total
        $orders = $this->orderRepository->getUserOrders($dataUser['user_id'], $perPage, $offset);
        $totalOrders = $this->orderRepository->countUserOrders($dataUser['user_id']);
        $totalPages = (int) ceil($totalOrders / $perPage);

        $content = __DIR__ . '/../../view/user/orders.php';
        $title = 'Mis pedidos';
        $pageName = 'orders';

        $assets = ['orders', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';
    }
}