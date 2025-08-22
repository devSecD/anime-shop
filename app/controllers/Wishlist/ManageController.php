<?php
namespace Controllers\Wishlist;

use Core\Controller;

use App\Helpers\ValidationHelper;
use App\Models\Wishlist\WishlistRepository;
use App\Models\Wishlist\WishlistModel;
use App\Helpers\ResponseHelper;
use App\Helpers\SessionHelper;

class ManageController extends Controller
{
    private $db;
    private $repository;

    public function __construct()
    {
        $this->db = $this->loadDB();
        $this->repository = new WishlistRepository(new WishlistModel($this->db));
        SessionHelper::start();
        SessionHelper::regenerate();
    }

    /**
     * Manejar agregar o eliminar un producto a la wishlist
     * POST /wishlist/manage
     * Parámetros esperados: action=add|remove, product_id
     */
    public function index()
    {
        // rechazar si no es metodo post
        ValidationHelper::rejectIfNotPost();

        // Obtiene el usuario
        $user = SessionHelper::getUser();
        $userId = $user['user_id'] ?? null;

        $action = $_POST['action'] ?? null;
        $productId = (int)($_POST['product_id'] ?? 0);

        if (!$userId || !$action || !$productId) {
            ResponseHelper::jsonResponse(
                [
                'success' => false,
                'message' => 'Parámetros inválidos o usuario no logueado.'
                ]
            );
        }


        switch($action) {
            case "add":
                $result = $this->repository->addItem($userId, $productId);
                $message = $result ? 'Producto agregado a la wishlist' : 'El producto ya está en la wishlist';
                break;
            case "remove":
                $result = $this->repository->removeItem($userId, $productId);
                $message = $result ? 'Producto eliminado de la wishlist' : 'No se pudo eliminar el producto';
                break;
            default:
                ResponseHelper::jsonResponse([
                    'success' => false,
                    'message' => 'Acción inválida'
                ]);
        }

        // Obtener el número actualizado de items en la wishlist
        $totalWishlistCount = $this->repository->getCount($userId);
        SessionHelper::set('user_wishlist_count', $totalWishlistCount);

        ResponseHelper::jsonResponse([
            'success' => $result,
            'count' => $totalWishlistCount,
            'message' => $message
        ]);
    }
}
