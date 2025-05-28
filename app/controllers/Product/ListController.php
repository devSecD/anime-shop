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

        // Capturar filtros desde la URL
        $filter = $_GET['filter'] ?? null;
        $sort = $_GET['sort'] ?? null;
        $category = $_GET['category'] ?? null;

        // Pagina actual
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $productsPerPage = 8;

        // Calcular offset
        $offset = ($currentPage - 1) * $productsPerPage;

        // Obtener productos filtrados y paginados
        // $products = $repo->getPaginatedProducts($productsPerPage, $offset);
        $products = $repo->getFilteredPaginatedProducts($filter, $sort, $category, $productsPerPage, $offset);

        // Obtener total de productos para paginacion (tambien filtrado)
        $totalProducts = $repo->countFilteredProducts($filter, $category);
        $totalPages = ceil($totalProducts / $productsPerPage);

        // Calcular total de paginas
        // $totalProducts = $repo->countAllProducts();
        // $totalPages = ceil($totalProducts / $productsPerPage);
        
        // Paso 5: Renderizar visto con informacion de paginacion
        $this->render(
            'products/list', [
                'products' => $products, 
                'currentPage' => $currentPage, 
                'totalPages' => $totalPages
            ]
        );

    }
}