<?php
namespace Controllers\Newsletter;

use Core\Controller;
use Models\Newsletter\NewsletterRepository;
use App\Helpers\ValidationHelper;

class SubscribeController extends Controller
{
    public function index()
    {
        // rechazar si no es metodo post
        ValidationHelper::rejectIfNotPost();

        $email = trim($_POST['email'] ?? '');

        // validar email
        if (!ValidationHelper::validateEmail($email)) {
            echo json_encode(['success' => false, 'message' => 'Email invalido']);
            return;
        }

        $db = $this->loadDB();

        // cargar modelo
        require_once __DIR__ . '/../../models/Newsletter/NewsletterRepository.php';
        $newsletterModel = new NewsletterRepository($db);

        $result = $newsletterModel->subscribe($email);

        if ($result) {
            echo json_encode(['success' => true, 'message' => '¡Gracias por suscribirte!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Este correo ya esta suscrito.']);
        }
    }
}