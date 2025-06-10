<?php
namespace Controllers\User;

use Core\Controller;
use Models\User\UserRepository;
use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;

class RegisterController extends Controller
{
    public function index()
    {
        $content = __DIR__ . '/../../view/user/register.php';
        $title = 'Crear cuenta - Anime Shop';
        $page = 'register';

        $assets = ['form']; // ejemplo para mas assets: $assets = ['form', 'datepicker', 'carousel'];

        include __DIR__ . '/../../view/layouts/base.php';
    }

    public function process()
    {
        // rechazar si no es metodo post
        ValidationHelper::rejectIfNotPost();

        $data = [
            'name' => $_POST['name'], 
            'email' => $_POST['email'],
            'password' => $_POST['password'], 
            'confirm_password' => $_POST['confirm_password']
        ];

        $errors = [];

        if ($error = ValidationHelper::required('nombre', $data['name']))
            $errors['name'] = $error;
        else if ($error = ValidationHelper::validateName($data['name']))
            $errors['name'] = $error;

        if ($error = ValidationHelper::required('correo electrónico', $data['email']))
            $errors['email'] = $error;
        else if ($error = ValidationHelper::validateEmail($data['email']))
            $errors['email'] = $error;

        if ($error = ValidationHelper::required('contraseña', $data['password']))
            $errors['password'] = $error;
        else if ($error = ValidationHelper::validatePasswordStrength($data['password']))
            $errors['password'] = $error;

        if ($error = ValidationHelper::required('confirmación de contraseña', $data['confirm_password']))
            $errors['confirm_password'] = $error;
        else if ($error = ValidationHelper::matchPasswords($data['password'], $data['confirm_password']))
            $errors['confirm_password'] = $error;

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors, 
            ]);
        }

        $db = $this->loadDB();
        $userRepo = new UserRepository($db);

        $userData = [
            'name' => $data['name'], 
            'email' => $data['email'], 
            'password' => $data['password']
        ];

        if ($userRepo->emailExists($userData['email'])) {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => 'El correo ya está registrado.'
            ]);
        }

        $result = $userRepo->register($userData);

        if ($result) {
            ResponseHelper::jsonResponse([
                'success' => true, 
                'message' => '¡Tu cuenta fue creada exitosamente! Serás redirigido al inicio de sesión en unos segundos...', 
                'redirect' => '/anime-shop/public/user/login'
            ]);
        } else {
            ResponseHelper::jsonResponse([
                'success' => false, 
                'message' => 'Algo salió mal y no pudimos registrar tu cuenta. Intenta de nuevo o contáctanos si el problema persiste.'
            ]);
        }

    }
}