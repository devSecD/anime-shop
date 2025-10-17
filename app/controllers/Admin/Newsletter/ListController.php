<?php
namespace Controllers\Admin\Newsletter;

use Core\Controller;
use Models\Newsletter\NewsletterRepository;
use App\Middleware\AdminMiddleware;

class ListController extends Controller
{
    private NewsletterRepository $newsletterRepo;
    private AdminMiddleware $auth;

    public function __construct()
    {
        $db = $this->loadDB();
        $this->newsletterRepo = new NewsletterRepository($db);
        $this->auth = new AdminMiddleware();
    }

    public function index(): void
    {
        $this->auth->handle();

        // Paginación
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $subscribersPerPage = 20;
        $offset = ($currentPage - 1) * $subscribersPerPage;

        // Conteo total de suscriptores
        $totalSubscribers = $this->newsletterRepo->getSubscribersCount();
        $totalPages = (int) ceil($totalSubscribers / $subscribersPerPage);

        // Obtener suscriptores paginados
        $subscribers = $this->newsletterRepo->getAllPaginated($subscribersPerPage, $offset);

        // Agregar un elemento al arreglo de suscriptores para identificar cuando son usuarios con cuenta o sin cuenta
        $subscribers = array_map(function($subscriber) {
            $subscriber['is_registered'] = $this->newsletterRepo->isUserRegistered($subscriber['email']);
            return $subscriber;
        }, $subscribers);

        // Preparar datos de paginación para la vista
        $pagination = [
            'currentPage' => $currentPage,
            'totalPages'  => $totalPages,
            'hasPrev'     => $currentPage > 1,
            'hasNext'     => $currentPage < $totalPages
        ];

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';
        $modalConfirmDelete = __DIR__ . '/../../../view/admin/components/modal-confirm-delete.php';

        $title = 'Suscriptores del newsletter';
        $page = 'admin_newsletter_list';
        extract(compact('subscribers', 'pagination', 'title', 'page', 'modalConfirmDelete'));

        include __DIR__ . '/../../../view/admin/newsletter/list.php';
    }
}
