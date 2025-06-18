<?php
namespace Controllers\User;

use Core\Controller;
use Models\User\UserRepository;
use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;
use Models\User\PasswordResetRepository;

class PasswordRecoveryController extends Controller
{
    public function index()
    {
        $content = __DIR__ . '/../../view/user/forgot_password.php';
        $title = 'Recuperar contraseña - Anime Shop';

        $page = 'forgot_password';

        $assets = ['form'];

        include __DIR__ . '/../../view/layouts/base.php';
    }

    public function process()
    {

        ValidationHelper::rejectIfNotPost();

        $data = [
            'email' => $_POST['email'],
        ];

        $errors = [];

        if ($error = ValidationHelper::required('correo electrónico', $data['email']))
            $errors['email'] = $error;
        else if ($error = ValidationHelper::validateEmail($data['email']))
            $errors['email'] = $error;

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors, 
            ]);
        }

        $db = $this->loadDB();
        $userRepo = new UserRepository($db);

        $user = $userRepo->getUserByEmail($data['email']);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expiresAt = (new \DateTime())->add(new \DateInterval('PT1H'))->format('Y-m-d H:i:s');
            $tokenRepo = new PasswordResetRepository($db);
            $tokenRepo->storeToken((int)$user['user_id'], $token, $expiresAt);

            $resetUrl = "http://localhost/anime-shop/public/user/reset-password?token={$token}&uid={$user['user_id']}";

            $this->sendRecoveryEmail($user['name'], $user['email'], $resetUrl);
        }

        // Siempre responder lo mismo por seguridad
        ResponseHelper::jsonResponse([
            'success' => true,
            'message' => 'Si el correo está registrado, recibirás instrucciones para reestablecer tu contraseña.',
        ]);

    }

    public function sendRecoveryEmail(string $name, string $email, string $url): bool
    {
        $mailer = new \App\Helpers\MailHelper();

        $body = "
            <p>Hola {$name},</p>
            <p>Hacé clic en el siguiente enlace para restablecer tu contraseña:</p>
            <p><a href=\"{$url}\">{$url}</a></p>
            <p>Este enlace expirará en 1 hora. Si no solicitaste el cambio, podés ignorar este mensaje.</p>
            <p>Gracias,<br>Anime Shop</p>
        ";

        return $mailer->send($email, 'Recuperar contraseña - Anime Shop', $body);

    }
}