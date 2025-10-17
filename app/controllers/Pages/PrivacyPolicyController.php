<?php
namespace Controllers\Pages;

use Core\Controller;

class PrivacyPolicyController extends Controller 
{
    // Vista de aviso de privacidad
    public function index() 
    {

        $content = __DIR__ . '/../../view/pages/privacy_policy.php';
        $title = 'Aviso de privacidad';

        $page = 'privacyPolicy';

        $assets = ['privacy', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';

    }
}