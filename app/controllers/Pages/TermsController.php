<?php
namespace Controllers\Pages;

use Core\Controller;

class TermsController extends Controller 
{
    public function __construct() 
    {
    }
    public function index() 
    {

        $content = __DIR__ . '/../../view/pages/terms_of_service.php';
        $title = 'Terminos de servicio';

        $page = 'termsOfService';

        $assets = ['terms', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';

    }
}