<?php
namespace Controllers\Wishlist;

use Core\Controller;

use App\Models\Wishlist\WishlistRepository;
use App\Models\Wishlist\WishlistModel;
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

    // Mostrar la wishlist del usuario
    public function index()
    {
        $user = SessionHelper::getUser();
        $userId = $user['user_id'] ?? null;

        $wishlistItems = [];

        if (!$userId) { // debemos usar el metodo LoggedIn del helper de sesion
            $loginMessage = 'Debes iniciar sesión para ver tu lista de deseos.';
        } else {
            $wishlistItems = $this->repository->getItems($userId);
        }

        $content = __DIR__ . '/../../view/wishlist/index.php';
        $title = 'Lista de deseos';
        $page = 'wishlist';
        $assets = ['wishlist', 'cart'];

        include __DIR__ . '/../../view/layouts/base.php';
    }
}
