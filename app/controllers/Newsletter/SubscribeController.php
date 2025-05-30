<?php
namespace Controllers\Newsletter;

use Core\Controller;
use Models\Newsletter\NewsletterModel;
use Models\Newsletter\NewsletterRepository;

class SubscribeController extends Controller
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            // validar formato de email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Aqui puedes rederigir o mostrar error, segun como manejes la respuesta
                echo json_encode(['success' => false, 'message' => 'Email invalido']);
                return;
            }

            $db = $this->loadDB();

            // cargar modelo
            require_once __DIR__ . '/../../models/Newsletter/NewsletterRepository.php';
            $newsletterModel = new NewsletterRepository($db);

            $result = $newsletterModel->subscribe($email);

            // header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['success' => true, 'message' => '¡Gracias por suscribirte!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Este correo ya esta suscrito.']);
            }
        } else {
            // si alguien accede directamente por GET
            http_response_code(405);
            echo "Metodo no permitido";
        }
    }
}