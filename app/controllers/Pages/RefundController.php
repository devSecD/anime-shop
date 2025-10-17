<?php
namespace Controllers\Pages;

use Core\Controller;

class RefundController extends Controller 
{
    // Vista de aviso de privacidad    
    public function index() 
    {

        $content = __DIR__ . '/../../view/pages/refund_policy.php';
        $title = 'Politica de reembolso';

        $page = 'refund_policy';

        $assets = ['refund', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';

    }
}