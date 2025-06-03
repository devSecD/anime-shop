<?php

/*
1. app\controllers\Home\IndexController.php
Podria refactorizar estas lineas:

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? null;
$sort = $_GET['sort'] ?? null;
$category = $_GET['category'] ?? null;

2. app\core\Router.php
Podria refactorizar estas lineas:
$url = $_GET['url'] ?? '';

Metiendolo en una clase y metodos donde podamos llamar ese metodo y no tener esas 4 lineas si no una que invoque al metodo, 
claro siempre y cuando se instancie la clase que contiene dicho meotdo

---------------------------------------------------------------------------------------------------------------------------------------------

app\controllers\Newsletter\SubscribeController.php
Podemos meter en validaciones (app\helpers\ValidationHelper.php) estas lineas de codigo:

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ...
} else {
    // si alguien accede directamente por GET
    http_response_code(405);
    echo "Metodo no permitido";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))

---------------------------------------------------------------------------------------------------------------------------------------------
app\controllers\Product\ListController.php
Al parecer esto ya no se esta usando en el proyecto ya que se sustituyo por el home controller.
Debo pedir ayudar a chatgpt para confirmar.
*/

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
