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

        $search = $_GET['search'] ?? '';
        $filter = $_GET['filter'] ?? null;
        $sort = $_GET['sort'] ?? null;
        $category = $_GET['category'] ?? null;

        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $productsPerPage = 8;
        $offset = ($currentPage - 1) * $productsPerPage; // Calcular offset

        //  encapsular todos los filtros en una clase tipo ProductQueryOptions
        $products = $repo->getFilteredPaginatedProducts($filter, $sort, $category, $productsPerPage, $offset, $search);
        $totalProducts = $repo->countFilteredProducts($filter, $category, $search);
        $totalPages = ceil($totalProducts / $productsPerPage);
        
        $this->render(
            'home/index', [
                'products' => $products, 
                'currentPage' => $currentPage, 
                'totalPages' => $totalPages
            ]
        );

    }
}
