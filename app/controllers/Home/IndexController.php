<?php
namespace Controllers\Home;

use Core\Controller;
use Models\Product\ProductRepository;
use App\Helpers\RequestHelper;

class IndexController extends Controller
{
    public function index()
    {
        $db = $this->loadDB();
        $repo = new ProductRepository($db);

        // Obtenemos todos los filtros y busqueda de acuerdo a lo que tengamos en $_GET para cada uno
        $params = RequestHelper::getQueryParams([
            'search' => '', 
            'filter' => null, 
            'sort' => null, 
            'category' => null
        ]);

        $search = $params['search'];
        $filter = $params['filter'];
        $sort = $params['sort'];
        $category = $params['category'];
        $pages = ['home', 'catalog'];

        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $productsPerPage = 8;
        $offset = ($currentPage - 1) * $productsPerPage; // Calcular offset

        $products = $repo->getFilteredPaginatedProducts($filter, $sort, $category, $productsPerPage, $offset, $search);
        $totalProducts = $repo->countFilteredProducts($filter, $category, $search);
        $totalPages = ceil($totalProducts / $productsPerPage);
        
        $this->render(
            'home/index', [
                'products' => $products, 
                'currentPage' => $currentPage, 
                'totalPages' => $totalPages, 
                'assets' => ['cart'], 
                'pages' => $pages
            ]
        );

    }
}
