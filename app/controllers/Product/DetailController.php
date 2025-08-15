<?php
namespace Controllers\Product;

use Core\Controller;
use Models\Product\ProductRepository;
use App\Helpers\RequestHelper;

class DetailController extends Controller
{
    protected $db;
    public function __construct() 
    {
        $this->db = $this->loadDB();
    }
    public function index()
    {
        $params = RequestHelper::requireGetParams(['product_id', 'product_name']);

        $productId = $params['product_id'];
        $productName  = $params['product_name'];

        $productRepo = new ProductRepository($this->db);

        // Obtenemos el producto principal con detalles
        $product = $productRepo->findDetailedById($productId);
        // $product = null;
        if (!$product) {
            http_response_code(404);
            $content = __DIR__ . '/../../view/errors/404.php';
            $title = '404 - Página no encontrada';
            $page  = 'error404';
            $assets = ['errors', 'cart'];
            include __DIR__ . '/../../view/layouts/base.php';
            exit;
        }

        $product['images'] = array_merge(
            [$product['image']], // imagen principal
            $productRepo->getProductImages($productId) // imágenes adicionales
        );

        // 2️⃣ Obtener reseñas
        $product['reviews'] = $productRepo->getReviews($productId);

        // 3️⃣ Obtener productos relacionados (por categoría o marca)
        $relatedProducts = $productRepo->getRelatedProducts(
            $product['category_id'], 
            $productId // Excluir el mismo producto
        );

        $content = __DIR__ . '/../../view/product/detail.php';
        $title = $product['name'] . ' - Anime Shop';
        $page = 'productDetail';
        $assets = ['product-detail', 'cart']; // si tienes CSS o JS específicos

        // Pasamos $product a la vista
        include __DIR__ . '/../../view/layouts/base.php';
    }
}
