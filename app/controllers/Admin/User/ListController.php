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

        $users = $this->userRepo->getAllUsers();

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';

        $title = 'Lista de usuarios';
        $page = 'admin_users_list';
        extract(compact('users', 'title', 'page'));

        include __DIR__ . '/../../../view/admin/users/list.php';
    }
}
?>