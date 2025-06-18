<?php
namespace Controllers\User;

use Core\Controller;
use Models\User\PasswordResetRepository;
use Models\User\UserRepository;
use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;

class ResetPasswordController extends Controller
{
    public function index()
    {

        $token = $_GET['token'] ?? '';
        $userId = (int)$_GET['uid'] ?? 0;

        $errors = [];

        if ($error = ValidationHelper::required('token', $token)) $errors['token'] = $error;

        if ($error = ValidationHelper::required('uid', $userId)) $errors['uid'] = $error;

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors, 
            ]);
        }

        $db = $this->loadDB();
        $tokenRepo = new PasswordResetRepository($db);

        if (!$tokenRepo->verifyToken($userId, $token)) {
            $content = __DIR__ . '/../../view/user/reset_invalid.php';
            $title = 'Enlace inválido - Anime Shop';
            $page = 'reset_invalid';
            $assets = ['form'];

            include __DIR__ . '/../../view/layouts/base.php';
            exit;
        }

        $content = __DIR__ . '/../../view/user/reset_password_form.php';
        $title = 'Cambiar contraseña - Anime Shop';
        $page = 'reset_password';
        $assets = ['form'];
        include __DIR__ . '/../../view/layouts/base.php';

    }

    public function process()
    {
        $token = $_POST['token'] ?? '';
        $userId = (int)$_POST['uid'] ?? 0;
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $errors = [];

        if ($error = ValidationHelper::required('token', $token)) $errors['token'] = $error;

        if ($error = ValidationHelper::required('uid', $userId)) $errors['uid'] = $error;

        if ($error = ValidationHelper::required('password', $password))
            $errors['password'] = $error;
        else if ($error = ValidationHelper::validatePasswordStrength($password))
            $errors['confirm_password'] = $error;

        if ($error = ValidationHelper::required('confirm_password', $confirm))
            $errors['confirm_password'] = $error;
        else if ($error = ValidationHelper::matchPasswords($password, $confirm))
            $errors['confirm_password'] = $error;

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors, 
            ]);
        }

        $db = $this->loadDB();
        $tokenRepo = new PasswordResetRepository($db);

        if (!$tokenRepo->verifyToken($userId, $token)) {
            ResponseHelper::jsonResponse(
                [
                    'success' => false, 
                    'message' => 'Enlace inválido o expirado'
                ]
            );
        }

        $userRepo = new UserRepository($db);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userRepo->updatePassword($userId, $hash);

        $tokenRepo->consumeToken($userId, $token);

        ResponseHelper::jsonResponse([
            'success' => true, 
            'message' => 'Contraseña actualizada correctamente', 
                'redirect' => '/anime-shop/public/user/login'
        ]);

    }
}