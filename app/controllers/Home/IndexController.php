<?php
namespace Controllers\Home;

use Core\Controller;
use Models\Product\ProductRepository;

class IndexController extends Controller
{
    public function index()
    {
        $db = $this->loadDB();
        $repo = new ProductRepository($db);

        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $productsPerPage = 8;
        $offset = ($currentPage - 1) * $productsPerPage;

        $products = $repo->getPaginatedProducts($productsPerPage, $offset);
        $totalProducts = $repo->countAllProducts();
        $totalPages = ceil($totalProducts / $productsPerPage);

        $this->render('home/index', [
            'products' => $products,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }
}
