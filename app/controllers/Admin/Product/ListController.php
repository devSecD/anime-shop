<?php
namespace Controllers\Admin\Product;

use Core\Controller;
use Models\Product\ProductRepository;
use App\Middleware\AdminMiddleware;
use Core\Paginator;

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
        $this->auth->handle();

        $totalProducts = $this->productRepo->countFilteredProducts('', null, '', false);
        $paginator = new Paginator($totalProducts, 8);

        $products = $this->productRepo->getFilteredPaginatedProducts(
            '', null, null, 
            $paginator->getLimit(), 
            $paginator->getOffset(), 
            '', 
            false
        );

        $pagination = [
            'currentPage' => $paginator->getCurrentPage(),
            'totalPages'  => $paginator->getTotalPages(),
            'hasPrev'     => $paginator->hasPrev(),
            'hasNext'     => $paginator->hasNext()
        ];

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';
        $modalConfirmDelete = __DIR__ . '/../../../view/admin/components/modal-confirm-delete.php';

        $title = 'Lista de productos';
        $page = 'admin_products_list';

        include __DIR__ . '/../../../view/admin/products/list.php';

    }
}
