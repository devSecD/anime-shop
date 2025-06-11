<?php
namespace Controllers\Home;
// controlador para el index/home
use Core\Controller;
use Models\Product\ProductRepository;
use App\Helpers\RequestHelper;
use App\Helpers\SessionHelper; // esto lo usare para despues

class IndexController extends Controller
{
    public function index()
    {

        // esto lo usare para despues

        // SessionHelper::start();

        // if (SessionHelper::isLoggedIn()) {
            // usuario autenticado
            // $user = SessionHelper::getUser(); // info del usuario
            // echo "<pre>";
            // print_r($user);
            // echo "</pre>";
        // }

        // esto lo usare para despues

        $db = $this->loadDB();
        $repo = new ProductRepository($db);

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
