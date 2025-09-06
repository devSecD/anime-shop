<?php
namespace Controllers\Wishlist;

use Core\Controller;

use App\Models\Wishlist\WishlistRepository;
use App\Models\Wishlist\WishlistModel;
use App\Helpers\ResponseHelper;
use App\Helpers\SessionHelper;

class ListController extends Controller
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
     * Mostrar la wishlist del usuario
     * GET /wishlist
     */
    public function index()
    {
        // Obtiene el usuario
        $user = SessionHelper::getUser();
        $userId = $user['user_id'] ?? null;

        $wishlistItems = [];

        if (!$userId) {
            // Usuario no logueado
            $loginMessage = 'Debes iniciar sesión para ver tu lista de deseos.';
        } else {
            // Usuario logueado: obtiene los items
            $wishlistItems = $this->repository->getItems($userId);
        }

        // Renderizar la vista (HTML + CSS)
        $content = __DIR__ . '/../../view/wishlist/index.php';
        $title = 'Lista ded deseos';
        $page = 'wishlist';
        $assets = ['wishlist', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';
    }
}
