<?php
namespace Controllers\User;

use Core\Controller;
use Models\User\UserRepository;
use Models\Newsletter\NewsletterRepository;
use Models\Order\OrderRepository;
use Models\Order\OrderModel;
use App\Helpers\SessionHelper;

class AccountController extends Controller 
{
    private $userRepository;
    private $newsletterRepository;
    private $db;
    public function __construct() 
    {
        $this->db = $this->loadDB();
        $this->userRepository = new UserRepository($this->db);
        $this->newsletterRepository = new NewsletterRepository($this->db);
    }

    public function index() 
    {
        $dataUser = SessionHelper::getUser();
        if (!$dataUser || empty($dataUser['user_id'])) { // cambiar por session is logged in del helper de sesion
            // Redirige si no hay sesión
            header('Location: /anime-shop/public/user/login');
            exit;
        }

        $userDetails = $this->userRepository->findById((int)$dataUser['user_id']);

        $newsletterSubscribed = false;
        if ($userDetails && !empty($user['email'])) {
            $newsletterSubscribed = $this->newsletterRepository->isEmailSubscribed($userDetails['email']);
        }

        $orderModel = new OrderModel($this->db);
        $orderRepository = new OrderRepository($orderModel);
        $orders = $orderRepository->getUserOrders($dataUser['user_id'], 5);

        $content = __DIR__ . '/../../view/user/account.php';
        $title = 'Mi cuenta';
        $page = 'account';

        $assets = ['account', 'orders', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';
    }
}