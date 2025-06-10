<?php
namespace Controllers\User;

class LoginController
{
    public function index()
    {
        $content = __DIR__ . '/../../view/user/login.php';
        $title = 'Iniciar sesion - Anime Shop';

        include __DIR__ . '/../../view/layouts/base.php';
    }
}