<?php
namespace Controllers\Admin\Newsletter;

use Core\Controller;
use Models\Newsletter\NewsletterRepository;
use App\Middleware\AdminMiddleware;
use App\Helpers\ValidationHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\SecureLogger;

class DeleteController extends Controller
{
    private $db;
    protected $newsletterRepository;
    private AdminMiddleware $auth;
    private $logger;
    public function __construct()
    {
        $this->db = $this->loadDB();
        $this->newsletterRepository = new NewsletterRepository($this->db);
        $this->auth = new AdminMiddleware();
    }

    public function index()
    {
        try {
            $this->auth->handle();
            $this->logger = new SecureLogger();

            // Rechazar si no es POST
            ValidationHelper::rejectIfNotPost();

            $email = $_POST['id'] ?? null;

            if (!$email) {
                ResponseHelper::jsonResponse([
                    'success' => false,
                    'message' => 'ID de suscriptor inválido.'
                ]);
            }

            $deleted = $this->newsletterRepository->deleteById($email);

            if ($deleted) {
                ResponseHelper::jsonResponse([
                    'success' => true,
                    'message' => 'Suscriptor eliminado correctamente.'
                ]);
            } else {
                ResponseHelper::jsonResponse([
                    'success' => false,
                    'message' => 'No se pudo eliminar el suscriptor. Verifique que exista.'
                ]);
            }

        } catch (\Exception $e) {
            $this->logger->write('❌ Ocurrio un error inesperado al eliminar la subscripcion', [
                'id_subscripcion' => $email,
                'response' => $deleted
            ]);
        }
    }
}
