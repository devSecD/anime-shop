<?php
namespace Controllers\User;

use Core\Controller;
use Models\User\UserRepository;
use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\SessionHelper;

class EditController extends Controller
{
    private $db;
    private $userRepository;
    public function __construct() 
    {
        $this->db = $this->loadDB();
        $this->userRepository = new UserRepository($this->db);
    }
    public function index()
    {
        $dataUser = SessionHelper::getUser();
        if (!$dataUser || empty($dataUser['user_id'])) {
            header('Location: /anime-shop/public/user/login');
            exit;
        }

        $userDetails = $this->userRepository->findById((int)$dataUser['user_id']);

        $content = __DIR__ . "/../../view/user/edit.php";

        $title = 'Edicion de mi cuenta';
        $page = 'accountEdit';

        $assets = ['accountEdit', 'form', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';
    }

    public function update()
    {
        $dataUser = SessionHelper::getUser();
        if (!$dataUser || empty($dataUser['user_id'])) {
            header('Location: /anime-shop/public/user/login');
            exit;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        $errors = [];

        $error = ValidationHelper::required('Nombre', $data['name']) ?? ValidationHelper::validateName($data['name']);
        if ($error) $errors['name'] = $error;

        $error = ValidationHelper::required('Email', $data['email']) ?? ValidationHelper::validateEmail($data['email']);
        if ($error) $errors['email'] = $error;

        if ($data['email'] !== $dataUser['email']) {
            $existingUser = $this->userRepository->findByEmail($data['email']);
            if ($existingUser) $errors['email'] = "El correo ya está en uso por otro usuario PHP.";
        }

        if (!empty($data['phone'])) {
            $error = ValidationHelper::validatePhone($data['phone']);
            if ($error) $errors['phone'] = $error;
        }

        if (!empty($data['password'])) {
            $error = ValidationHelper::validatePasswordStrength($data['password']);
            if ($error) $errors['password'] = $error;
        }

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors
            ]);
        }

        $passwordHash = !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password_hash' => $passwordHash
        ];

        $result = $this->userRepository->update($dataUser['user_id'], $updateData);

        if ($result['success']) {

            if (!empty($passwordHash)) {
                SessionHelper::destroy(); // Mata la sesión actual
                ResponseHelper::jsonResponse([
                    'success' => true,
                    'message' => 'Tu contraseña fue actualizada. Inicia sesión nuevamente.',
                    'redirect' => '/anime-shop/public/user/login'
                ]);
            }

            SessionHelper::setUserSession([
                'user_id' => $dataUser['user_id'],
                'name'    => $data['name'],
                'email'   => $data['email'],
                'phone'   => $data['phone'],
                'roles'   => $dataUser['roles']
            ]);

            ResponseHelper::jsonResponse([
                'success' => true,
                'message' => '¡Perfil actualizado exitosamente!',
                'redirect' => '/anime-shop/public/user/account'
            ]);

        } else {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }
}
