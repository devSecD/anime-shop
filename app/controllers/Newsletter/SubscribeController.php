<?php
namespace Controllers\Newsletter;

use Core\Controller;
use Models\Newsletter\NewsletterRepository;
use App\Helpers\ValidationHelper;

class SubscribeController extends Controller
{
    // Metodo para supscripcion desde el footer
    public function index()
    {
        ValidationHelper::rejectIfNotPost();

        $email = trim($_POST['email'] ?? '');

        if (ValidationHelper::validateEmail($email)) {
            echo json_encode(['success' => false, 'message' => 'Email invalido']); // sustituir por json response
            return;
        }

        $db = $this->loadDB();

        require_once __DIR__ . '/../../models/Newsletter/NewsletterRepository.php';
        $newsletterModel = new NewsletterRepository($db);

        $result = $newsletterModel->subscribe($email);

        if ($result) {
            echo json_encode(['success' => true, 'message' => '¡Gracias por suscribirte!']); // sustituir por json response
        } else {
            echo json_encode(['success' => false, 'message' => 'Este correo ya esta suscrito.']); // sustituir por json response
        }
    }
}