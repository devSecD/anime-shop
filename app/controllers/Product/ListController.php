<?php
namespace Controllers\Product;

use Core\Controller;
use Models\Product\ProductRepository;

class ListController extends Controller
{
    public function index()
    {
        $db = $this->loadDB();
        $repo = new ProductRepository($db);
        // $products = $repo->getCatalog();

        // Paso 1: Pagina actual
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $productsPerPage = 8;

        // Paso 2: Calcular offset
        $offset = ($currentPage - 1) * $productsPerPage;

        // Paso 3: Obtener productos paginados
        $products = $repo->getPaginatedProducts($productsPerPage, $offset);

        // Paso 4: Calcular total de paginas
        $totalProducts = $repo->countAllProducts();
        $totalPages = ceil($totalProducts / $productsPerPage);
        
        // Paso 5: Renderizar visto con informacion de paginacion
        $this->render(
            'products/list', [
                'products' => $products, 
                'currentPage' => $currentPage, 
                'totalPages' => $totalPages
            ]
        );

        // $this->render('products/list', ['products' => $products]);
    }
}