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

class UpdateController extends Controller
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

    public function index(int $id): void
    {
        $this->auth->handle();

        if (!$id) {
            // Redireccionar o lanzar error
            header('Location: /admin/products'); // pediente
            exit;
        }

        $product = $this->productRepo->findById($id);
        $categories = $this->categoryRepo->getAll();
        $brands = $this->brandRepo->getAll();

        if (!$product) {
            // Redireccionar o lanzar error si no se encuentra
            header('Location: /admin/products'); // pediente
            exit;
        }

        $html_head = __DIR__ . '/../../../view/admin/components/html_head.php';
        $sidebar = __DIR__ . '/../../../view/admin/components/sidebar.php';
        $product_from = __DIR__ . '/../../../view/admin/components/product_from.php';

        $assets = ['form'];
        $title = 'Editar producto';
        $page = 'updateProduct';

        $urlForm = FormHelper::getProductFormAction($page);
        $titleForm = FormHelper::getProductFormTitle($page);
        $textButton = FormHelper::getProductFormTextButton($page);
        $idForm = FormHelper::getProductFormId($page);
        $idButtonForm = FormHelper::getProductFormButtonId($page);

        extract(compact('title', 'page', 'product', 'categories', 'brands', 'urlForm', 'titleForm', 'product_from', 'textButton', 'idForm', 'idButtonForm'));

        include __DIR__ . '/../../../view/admin/products/edit.php';
    }

    public function process()
    {
        $this->auth->handle();

        // Rechazar si no es POST
        ValidationHelper::rejectIfNotPost();

        // ID del producto a actualizar
        $productId = $_POST['product_id'] ?? null;

        if (!$productId || !is_numeric($productId)) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => 'ID de producto no válido.'
            ]);
        }

        $data = [
            'product_id' => $productId,
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? '',
            'price_discounted' => $_POST['price_discounted'] ?? null,
            'stock' => $_POST['stock'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'brand_id' => $_POST['brand_id'] ?? '',
            'is_on_sale' => $_POST['is_on_sale'] ?? null,
            'is_preorder' => $_POST['is_preorder'] ?? null,
        ];

        // 👇 esta línea es clave
        $data['image'] = $_FILES['image'] ?? null;

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

        // Validación condicional de imagen
        $uploadImage = isset($data['image']) && is_array($data['image']) && $data['image']['error'] !== UPLOAD_ERR_NO_FILE;

        if ($uploadImage) {
            if ($error = ValidationHelper::validateImageUpload($data['image'], 'imagen del producto')) 
                $errors['image'] = $error;
        }

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

        // ✅ SUBIR NUEVA IMAGEN (si corresponde)
        $fileName = null;

        if ($uploadImage) {
            $uploadService = new UploadService();
            $fileName = $uploadService->upload($_FILES['image'], 'products');

            if ($fileName === null) {
                ResponseHelper::jsonResponse([
                    'success' => false,
                    'message' => 'Ocurrió un error al guardar la imagen. Intenta nuevamente.'
                ]);
            }
        }

        $data['is_on_sale'] = (int) $data['is_on_sale'];
        $data['is_preorder'] = (int) $data['is_preorder'];

        // Preparar datos para actualización
        $db = $this->loadDB();
        $productRepo = new ProductRepository($db);

        $updateData = [
            'product_id' => $data['product_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'price_discounted' => $data['price_discounted'],
            'stock' => $data['stock'],
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'is_on_sale' => $data['is_on_sale'],
            'is_preorder' => $data['is_preorder'],
        ];

        if ($uploadImage) {
            $updateData['image'] = $fileName;
        }

        $result = $productRepo->update($updateData);

        if ($result['success']) {
            ResponseHelper::jsonResponse([
                'success' => true,
                'message' => '¡Producto actualizado exitosamente!',
                'redirect' => '/anime-shop/public/admin/products'
            ]);
        } else {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }

}