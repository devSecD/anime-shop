<?php
namespace App\Middleware;

use Models\User\UserRepository;
use App\Helpers\SessionHelper;
use App\Helpers\DBConnection;

 class AdminMiddleware 
 {
    protected UserRepository $userRepo;

    public function __construct()
    {
        $pdo = DBConnection::get();
        $this->userRepo = new UserRepository($pdo);
    }

    public function handle(): void 
    {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /anime-shop/public/user/login');
            exit;
        }

        // Obtiene el usuario
        $user = SessionHelper::getUser();
        $userId = $user['user_id'] ?? null;

        $roles = $this->userRepo->getUserRoles($userId);

        if (!in_array('admin', $roles)) {
            http_response_code(403);
            echo "Acceso denegado: No tiene permisos para esta sección.";
            exit;
        }

    }
}