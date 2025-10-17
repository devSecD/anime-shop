<?php
namespace Controllers\Admin\Product;

use Core\Controller;
use Models\Product\ProductRepository;
use App\Helpers\ValidationHelper;
use App\Helpers\ResponseHelper;
use App\Middleware\AdminMiddleware;

class DeleteController extends Controller
{
    private $db;
    private AdminMiddleware $auth;
    public function __construct() 
    {
        $this->db = $this->loadDB();
        $this->auth = new AdminMiddleware();
    }

    public function index()
    {
        $this->auth->handle();

        // rechazar si no es metodo post
        ValidationHelper::rejectIfNotPost();

        $id_product = (int) $_POST['id'];

        if (!isset($id_product)) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => 'ID de producto no recibido.'
            ]);
        }

        if (ValidationHelper::mustBePositiveInt('id', $id_product)) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => 'ID de producto inválido.'
            ]);
        }

        $repo = new ProductRepository($this->db);
        $response = $repo->deleteProduct($id_product);

        if(!$response['success']) {
            ResponseHelper::jsonResponse([
                'success' => false,
                'message' => $response['message'],
            ]);
        }

        ResponseHelper::jsonResponse([
            'success' => true,
            'message' => 'Producto desactivado.',
            'product_was_deactivated' => true
        ]);
    }
}
