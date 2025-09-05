<?php
namespace Controllers\Pages;

use Core\Controller;
use Models\FAQ\FAQModel;
use Models\FAQ\FAQRepository;

class FAQController extends Controller 
{
    private $repository;
    private $db;
    public function __construct() 
    {
        $this->db = $this->loadDB();
        $this->repository = new FAQRepository($this->db);
    }
    public function index() 
    {
        $faqs = $this->repository->getAllFAQs();

        $content = __DIR__ . '/../../view/pages/faq.php';
        $title = 'Preguntas frecuentes';

        $page = 'FAQ';

        $assets = ['faq', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';

    }
}