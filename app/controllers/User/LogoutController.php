<?php
namespace Controllers\User;

use Core\Controller;
use App\Helpers\SessionHelper;

class LogoutController extends Controller
{
    public function index()
    {
        SessionHelper::start();
        SessionHelper::destroy();

        // redirigir al home
        header('Location: /anime-shop/public');
        exit;
    }
}