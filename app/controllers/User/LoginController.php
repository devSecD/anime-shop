<?php
namespace Controllers\User;

use Core\Controller;
use Models\User\UserRepository;
use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\SessionHelper;

class LoginController extends Controller
{
    protected UserRepository $userRepo;

    public function __construct()
    {
        $db = $this->loadDB();
        $this->userRepo = new UserRepository($db);
    }

    public function index()
    {
        $content = __DIR__ . '/../../view/user/login.php';
        $title = 'Iniciar sesion - Anime Shop';

        $page = 'login';

        $assets = ['form']; // ejemplo para mas assets: $assets = ['form', 'datepicker', 'carousel'];

        include __DIR__ . '/../../view/layouts/base.php';
    }

    public function process()
    {
        // rechazar si no es metodo post
        ValidationHelper::rejectIfNotPost();

        $data = [
            'email' => $_POST['email'],
            'password' => $_POST['password'], 
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

        $userData = [
            'email' => $data['email'], 
            'password' => $data['password']
        ];

        $result = $userRepo->attemptLogin($userData['email'], $userData['password']);

        // Obtener roles
        $roles = $this->userRepo->getUserRoles((int)$result['user']['user_id']);

        if (!$result['success']) 
            ResponseHelper::jsonResponse($result);

        // iniciar sesion
        SessionHelper::start();
        SessionHelper::regenerate();
        // SessionHelper::set('user', $result['user']); // aqui faltaria meter el role $roles
        // Guardar sesión
        SessionHelper::setUserSession([
            'user_id' => $result['user']['user_id'],
            'name' => $result['user']['name'],
            'email' => $result['user']['email'],
            'roles' => $roles
        ]);

        // Redirección según rol
        if (in_array('admin', $roles)) {
            ResponseHelper::jsonResponse([
                    'success' => true, 
                    'message' => $result['message'], 
                    'redirect' => '/anime-shop/public/admin/dashboard'
            ]);
        } else {
            ResponseHelper::jsonResponse([
                    'success' => true, 
                    'message' => $result['message'], 
                    'redirect' => '/anime-shop/public/'
            ]);
        }
    }

}