<?php
namespace Controllers\Admin\User;

use Core\Controller;
use Models\User\UserRepository;
use App\Middleware\AdminMiddleware;

class ListController extends Controller
{
    private UserRepository $userRepo;
    private AdminMiddleware $auth;

    public function __construct()
    {
        $db = $this->loadDB();
        $this->userRepo = new UserRepository($db);
        $this->auth = new AdminMiddleware();
    }

    public function index(): void
    {
        $this->auth->handle();

        // Filtro opcional por rol
        $role = $_GET['role'] ?? null;

        // Paginación
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $usersPerPage = 20;
        $offset = ($currentPage - 1) * $usersPerPage;

        // Total de usuarios (el repo decide si filtra por rol o no)
        $totalUsers = $this->userRepo->countUsers($role);
        $totalPages = (int) ceil($totalUsers / $usersPerPage);

        // Obtener usuarios paginados
        $users = $this->userRepo->getUsers($role, $usersPerPage, $offset);

        // Preparar datos de paginación para la vista
        $pagination = [
            'currentPage' => $currentPage,
            'totalPages'  => $totalPages,
            'hasPrev'     => $currentPage > 1,
            'hasNext'     => $currentPage < $totalPages
        ];

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';

        $title = 'Lista de usuarios';
        $page = 'admin_users_list';
        extract(compact('users', 'pagination', 'role', 'title', 'page'));

        include __DIR__ . '/../../../view/admin/users/list.php';
    }
}