<?php
namespace Controllers\Admin\Product;

use Core\Controller;
use App\Middleware\AdminMiddleware;
use Models\Product\ProductRepository;
use Models\Category\CategoryModel;
use Models\Category\CategoryRepository;
use Models\Brand\BrandModel;
use Models\Brand\BrandRepository;
use App\Helpers\ValidationHelper;
use App\Helpers\StringHelper;
use App\Helpers\ResponseHelper;
use App\Services\UploadService;
use App\Helpers\FormHelper;

class CreateController extends Controller
{
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;
    private BrandRepository $brandRepo;
    private AdminMiddleware $auth;

    public function __construct()
    {
        $db = $this->loadDB();
        $this->productRepo = new ProductRepository($db);
        $categoryModel = new categoryModel($db);
        $this->categoryRepo = new CategoryRepository($categoryModel);
        $brandModel = new brandModel($db);
        $this->brandRepo = new BrandRepository($brandModel);
        $this->auth = new AdminMiddleware();
    }

    public function index(): void
    {
        // Verificar permisos admin
        $this->auth->handle();

        $categories = $this->categoryRepo->getAll();
        $brands = $this->brandRepo->getAll();

        if (!isset($product)) {
            $product = [];
        }

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';
        $product_from = __DIR__ . '/../../../view/admin/components/product_from.php';

        $assets = ['form'];
        $title = 'Agregar nuevo producto';
        $page = 'registerProduct';

        $urlForm = FormHelper::getProductFormAction($page);
        $titleForm = FormHelper::getProductFormTitle($page);
        $textButton = FormHelper::getProductFormTextButton($page);
        $idForm = FormHelper::getProductFormId($page);
        $idButtonForm = FormHelper::getProductFormButtonId($page);

        extract(compact('title', 'page', 'titleForm', 'textButton', 'idForm', 'idButtonForm'));

        include __DIR__ . '/../../../view/admin/products/create.php';
    }

    public function process()
    {
        $this->auth->handle();

        // Rechazar si no es POST
        ValidationHelper::rejectIfNotPost();

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? '',
            'price_discounted' => $_POST['price_discounted'] ?? null,
            'stock' => $_POST['stock'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'brand_id' => $_POST['brand_id'] ?? '',
            'image' => $_FILES['image'], 
            'is_on_sale' => $_POST['is_on_sale'] ?? null,
            'is_preorder' => $_POST['is_preorder'] ?? null,
        ];

        $errors = [];

        // Validaciones
        if ($error = ValidationHelper::required('nombre del producto', $data['name']))
            $errors['name'] = $error;

        if ($error = ValidationHelper::required('descripción', $data['description']))
            $errors['description'] = $error;

        if ($error = ValidationHelper::required('precio', $data['price']))
            $errors['price'] = $error;
        else if ($error = ValidationHelper::validatePositive($data['price']))
            $errors['price'] = $error;

        if ($data['price_discounted'] !== null && $data['price_discounted'] !== '') {
            if ($error = ValidationHelper::validatePositive($data['price_discounted']))
                $errors['price_discounted'] = $error;
        }

        if ($error = ValidationHelper::required('stock', $data['stock']))
            $errors['stock'] = $error;
        else if ($error = ValidationHelper::mustBePositiveInt('stock', $data['stock']))
            $errors['stock'] = $error;

        if ($error = ValidationHelper::required('categoría', $data['category_id']))
            $errors['category_id'] = $error;

        if ($error = ValidationHelper::required('marca', $data['brand_id']))
            $errors['brand_id'] = $error;

        // Validación de imagen
        if ($error = ValidationHelper::validateImageUpload($data['image'], 'imagen del producto')) 
            $errors['image'] = $error;

        // Validación de booleanos
        if ($error = ValidationHelper::mustBeOptionalBoolean($data['is_on_sale'], 'en oferta'))
            $errors['is_on_sale'] = $error;

        if ($error = ValidationHelper::mustBeOptionalBoolean($data['is_preorder'], 'preventa'))
            $errors['is_preorder'] = $error;

        if (!empty($errors)) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => StringHelper::implodeArray($errors, "\n"),
                'errors' => $errors
            ]);
        }

        // ✅ SUBIR IMAGEN FÍSICAMENTE (solo si pasó validación)
        $uploadService = new UploadService();
        $fileName = $uploadService->upload($_FILES['image'], 'products');

        if ($fileName === null) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => 'Ocurrió un error al guardar la imagen. Intenta nuevamente.'
            ]);
        }

        $data['is_on_sale'] = (int) $data['is_on_sale'];
        $data['is_preorder'] = (int) $data['is_preorder'];

        // Insertar el producto
        $db = $this->loadDB();
        $productRepo = new ProductRepository($db);

        $insertData = [
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'price_discounted' => $data['price_discounted'], // si es vacío lo pone null
            'stock' => $data['stock'],
            'image' => $fileName, 
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'is_on_sale' => $data['is_on_sale'],
            'is_preorder' => $data['is_preorder'],
        ];

        $result = $productRepo->create($insertData);

        if ($result['success']) {
            ResponseHelper::jsonResponse([
                'success' => true,
                'message' => '¡Producto creado exitosamente!',
                'redirect' => '/anime-shop/public/admin/products'
            ]);
        } else {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el producto. Intenta de nuevo más tarde.'
            ]);
        }
    }
}
