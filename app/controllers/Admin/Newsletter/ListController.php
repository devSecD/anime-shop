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

        $subscribers = $this->newsletterRepo->getAll();

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';

        $title = 'Suscriptores del newsletter';
        $page = 'admin_newsletter_list';
        extract(compact('subscribers', 'title', 'page'));

        include __DIR__ . '/../../../view/admin/newsletter/list.php';
    }
}
