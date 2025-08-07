<?php
namespace Controllers\Admin\Product;

use Core\Controller;
use Models\Product\ProductRepository;
use App\Middleware\AdminMiddleware;

class ListController extends Controller
{
    private ProductRepository $productRepo;
    private AdminMiddleware $auth;

    public function __construct()
    {
        $db = $this->loadDB();
        $this->productRepo = new ProductRepository($db);
        $this->auth = new AdminMiddleware();
    }

    public function index(): void
    {
        // Verificación de permisos admin (middleware o helper)
        $this->auth->handle();

        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $productsPerPage = 8; // Puedes ajustar este número si quieres mostrar más o menos por página
        $offset = ($currentPage - 1) * $productsPerPage;

        // Obtener productos paginados (por ahora sin filtros)
        $products = $this->productRepo->getFilteredPaginatedProducts('', null, null, $productsPerPage, $offset, '');
        $totalProducts = $this->productRepo->countFilteredProducts('', null, '');
        $totalPages = ceil($totalProducts / $productsPerPage);

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';
        $modalConfirmDelete = __DIR__ . '/../../../view/admin/components/modal-confirm-delete.php';

        $title = 'Lista de productos';
        $page = 'admin_products_list';

        extract(compact('products', 'currentPage', 'totalPages', 'title', 'page'));

        include __DIR__ . '/../../../view/admin/products/list.php';
    }
}
