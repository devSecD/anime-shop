<?php
namespace Controllers\Pages;

use Core\Controller;

class PrivacyPolicyController extends Controller 
{
    public function __construct() 
    {
    }
    public function index() 
    {

        $content = __DIR__ . '/../../view/pages/privacy_policy.php';
        $title = 'Aviso de privacidad';

        $page = 'privacyPolicy';

        $assets = ['privacy', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';

    }
}