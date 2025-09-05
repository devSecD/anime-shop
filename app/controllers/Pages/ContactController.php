<?php
namespace Controllers\Pages;

use Core\Controller;
use Models\Contact\Contact;
use Models\Contact\ContactRepository;

use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;

use PDO;

class ContactController extends Controller
{
    private $repository;
    private $db;
    public function __construct()
    {
        $this->db = $this->loadDB();
        $this->repository = new ContactRepository($this->db);
    }

    /**
     * Muestra la página de contacto
     */
    public function index()
    {
        $content = __DIR__ . '/../../view/pages/contact.php';
        $title = 'Contacto';

        $page = 'contactForm';

        $assets = ['contact', 'cart', 'form'];

        include __DIR__ . '/../../view/layouts/base.php';
    }

    /**
     * Procesa el envío del formulario
     */
    public function send()
    {
        // rechazar si no es metodo post
        ValidationHelper::rejectIfNotPost();

        $data = [
            'name' => $_POST['name'], 
            'email' => $_POST['email'],
            'subject' => $_POST['subject'], 
            'message' => $_POST['message'], 
            'website' => $_POST['website'], 
        ];

        // Honeypot
        if (!empty($data['website'])) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => 'Se detectó actividad sospechosa.'
            ]);
        }

        // validaciones de los campos del formulario de contacto
        $errors = [];

        if ($error = ValidationHelper::required('nombre', $data['name']))
            $errors['name'] = $error;
        else if ($error = ValidationHelper::validateName($data['name']))
            $errors['name'] = $error;

        if ($error = ValidationHelper::required('correo electrónico', $data['email']))
            $errors['email'] = $error;
        else if ($error = ValidationHelper::validateEmail($data['email']))
            $errors['email'] = $error;

        if ($error = ValidationHelper::required('asunto', $data['subject']))
            $errors['subject'] = $error;
        else if ($error = ValidationHelper::validateText($data['subject'], 3, 100)) // ejemplo con min 3, max 100
            $errors['subject'] = $error;

        if ($error = ValidationHelper::required('mensaje', $data['message']))
            $errors['message'] = $error;
        else if ($error = ValidationHelper::validateText($data['message'], 10, 1000)) // ejemplo con min 10, max 1000
            $errors['message'] = $error;

        // Validar reCAPTCHA v3
        $recaptchaConfig = require __DIR__ . '/../../config/recaptcha.php';
        $recaptchaToken = $_POST['recaptcha_token'] ?? '';
        $recaptchaResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaConfig['secret_key']}&response={$recaptchaToken}");
        $recaptchaData = json_decode($recaptchaResponse, true);

        if (!$recaptchaData['success'] || $recaptchaData['score'] < 0.5) {
            $errors['recaptcha'] = 'Error de validación de reCAPTCHA. Intenta de nuevo.';
        }

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors, 
            ]);
        }

        $success = $this->repository->submitMessage($_POST);

        ResponseHelper::jsonResponse([
            'success' => $success,
            'message' => $success
                ? "¡Gracias por contactarnos! Pronto nos pondremos en contacto."
                : "Hubo un error al enviar tu mensaje. Intenta de nuevo más tarde.",
        ]);

    }
}
