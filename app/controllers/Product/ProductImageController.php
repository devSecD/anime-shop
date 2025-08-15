<?php
    // controlador exclusivo para guardar en bd y fisicamente imagenes de un producto
    namespace Controllers\Product;

    use Core\Controller;
    use Models\Product\ProductRepository;

    class ProductImageController extends Controller {
        protected $db;
        public function __construct() 
        {
            $this->db = $this->loadDB();
        }

        public function generate() {
            $productRepo = new ProductRepository($this->db);
            $productRepo->generateImagesWithWatermark();
            echo "Proceso completado desde MVC + Repository.\n";
        }
    }
?>