<?php
namespace Controllers\User;

use Core\Controller;
use Models\Newsletter\NewsletterRepository;
use App\Helpers\ResponseHelper;
use App\Helpers\SessionHelper;

class NewsletterController extends Controller
{
    private $db;
    protected $newsletterRepo;
    protected $userEmail;

    public function __construct()
    {
        $this->db = $this->loadDB();
        $this->newsletterRepo = new NewsletterRepository($this->db);

        $user = SessionHelper::getUser();
        if (!$user) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ]);
        }

        $this->userEmail = $user['email'];
    }

    // NewsletterController.php
    public function status()
    {
        try {
            $isSubscribed = $this->newsletterRepo->isEmailSubscribed($this->userEmail);

            ResponseHelper::jsonResponse([
                'success' => true,
                'subscribed' => $isSubscribed
            ]);
        } catch (\Exception $e) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'subscribed' => false,
                'message' => 'No se pudo obtener el estado del newsletter.'
            ]);
        }
    }

    // Toggle subscription
    public function toggle()
    {
        $isSubscribed = $this->newsletterRepo->isEmailSubscribed($this->userEmail);

        if ($isSubscribed) {
            // Desuscribir
            $this->newsletterRepo->deleteByEmail($this->userEmail); // crearemos método en repo
            $status = false;
            $message = 'Te has desuscrito del newsletter.';
        } else {
            // Suscribir
            $this->newsletterRepo->subscribe($this->userEmail);
            $status = true;
            $message = '¡Te has suscrito al newsletter!';
        }

        ResponseHelper::jsonResponse([
            'success' => true,
            'message' => $message,
            'subscribed' => $status
        ]);
    }
}
