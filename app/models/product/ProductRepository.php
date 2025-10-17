<?php
namespace Models\Product;

use PDO;

use App\Helpers\ValidationHelper;
use Exception;

class ProductRepository
{
    protected $productModel;

    public function __construct(PDO $db)
    {
        $this->productModel = new Product($db);
    }

    /**
     * Crea un nuevo producto validando negocio extra si es necesario
     * 
     * @param array $data Datos del producto ya validados desde el controlador
     * @return array Resultado ['success' => bool, 'product_id' => int|null, 'message' => string|null]
     */
    public function create(array $data): array
    {
        $regularPrice = is_numeric($data['price']) ? (float) $data['price'] : null;
        $discountedPrice = trim($data['price_discounted']) === '' ? null : (float) $data['price_discounted'];

        // Validar lógica de negocio: precio con descuento no mayor al normal
        $error = ValidationHelper::validateDiscountedPrice($discountedPrice, $regularPrice);

        if($error !== null) {
            return [
                'success' => false, 
                'product_id' => null, 
                'message' => $error
            ];
        }

        $data['price_discounted'] = $discountedPrice;

        $insertedId = $this->productModel->create($data);

        if ($insertedId === false) {
            return [
                'success' => false,
                'product_id' => null,
                'message' => 'Error al crear el producto en la base de datos.'
            ];
        }

        return [
            'success' => true,
            'product_id' => $insertedId,
            'message' => null
        ];
    }

     // Devuelve un producto o null si no existe
    public function findById(int $id): ?array
    {
        return $this->productModel->getById($id);
    }

     // Devuelve varios productos a partir de un array de IDs
    public function findByIds(array $ids): array
    {
        if (!$ids) return [];

        // Crea una cadena de placeholders "?, ?, ?" según la cantidad de IDs
        $in = implode(',', array_fill(0, count($ids), '?'));

        return $this->productModel->getByIds($ids, $in);
    }

    public function getFilteredPaginatedProducts($filter, $sort, $category, $limit, $offset, $search, $onlyActive = true)
    {
        $sql = "SELECT 
                p.*, 
                c.name AS category_name, 
                b.name AS brand_name
            FROM products p
            INNER JOIN categories c ON p.category_id = c.category_id
            INNER JOIN brands b ON p.brand_id = b.brand_id 
            WHERE 1=1";
        $conditions = [];
        $params = [];

        if ($onlyActive) {
            $conditions[] = "p.is_active = 1";
        }

        if ($filter === 'in-stock') {
            $conditions[] = "p.stock > 0";
        }

        if ($filter === 'discounted') {
            $conditions[] = "p.is_on_sale = 1 AND p.price_discounted IS NOT NULL";
        }

        if (!empty($category)) {
            $conditions[] = "p.category_id = :category_id";
            $params[':category_id'] = $category;
        }

        if (!empty($search)) {
            $conditions[] = "p.name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        if ($conditions) {
            $sql .= ' AND ' . implode(' AND ', $conditions);
        }

        $allowedSorts = ['newest', 'top-sellers'];

        if (in_array($sort, $allowedSorts)) {
            switch($sort) {
                case 'newest': $sql .= " ORDER BY p.created_at DESC"; break;
                case 'top-sellers': $sql .= " ORDER BY p.sold_count DESC"; break;
            }
        } else {
            $sql .= " ORDER BY p.name ASC";
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = (int)$limit;
        $params[':offset'] = (int)$offset;

        return $this->productModel->executeQuery($sql, $params);
    }
    public function countFilteredProducts($filter, $category, $search, $onlyActive = true)
    {
        $sql = "SELECT COUNT(*) FROM products WHERE 1=1";
        $conditions = [];
        $params = [];

        if ($onlyActive) {
            $conditions[] = "is_active = 1";
        }

        if ($filter === 'in-stock') {
            $conditions[] = "stock > 0";
        }

        if ($filter === 'discounted') {
            $conditions[] = "is_on_sale = 1 AND price_discounted IS NOT NULL";
        }

        if (!empty($category)) {
            $conditions[] = "category_id = :category_id";
            $params[':category_id'] = $category;
        }

        if (!empty($search)) {
            $conditions[] = "name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        if ($conditions) {
            $sql .= ' AND ' . implode(' AND ', $conditions);
        }

        return $this->productModel->executeCount($sql, $params);
    }

    public function decreaseStock(int $productId, int $qty, int $stock): bool
    {
        if ($qty <= 0 || $qty > $stock) return false;

        // Podrías agregar más validaciones aquí si quieres

        return $this->productModel->decreaseStock($productId, $qty);
    }

    public function increaseSoldCount(int $productId, int $qty, int $stock): bool
    {
        if ($qty <= 0 || $qty > $stock) return false;

        // Podrías agregar más validaciones aquí si quieres

        return $this->productModel->increaseSoldCount($productId, $qty);
    }


    public function createProduct(array $data): bool 
    {
        return $this->productModel->createProduct($data);
    }

    public function update(array $data): array
    {
        // Validar lógica de negocio: precio con descuento no mayor al normal
        $discounted = isset($data['price_discounted']) && $data['price_discounted'] !== ''
            ? (float)$data['price_discounted'] 
            : null;

        $regular = isset($data['price']) && $data['price'] !== ''
            ? (float)$data['price'] 
            : null;

        $error = ValidationHelper::validateDiscountedPrice($discounted, $regular);

        if($error !== null) {
            return [
                'success' => false, 
                'product_id' => $data['product_id'], 
                'message' => $error
            ];
        }

        // Verificar si el producto existe antes de actualizar
        $product = $this->productModel->getById($data['product_id']);

        if (!$product) {
            return [
                'success' => false,
                'message' => 'El producto no existe.'
            ];
        }

        // Si no hay nueva imagen, mantener la anterior
        if (!isset($data['image'])) {
            $data['image'] = $product['image'];
        } else {
            // Eliminar la imagen anterior del disco
            unlink(dirname(__DIR__, 3) . '/public/assets/images/products/' . $product['image']);
        }

        $data['price_discounted'] = $data['price_discounted'] === '' ? null : $data['price_discounted'];

        $success = $this->productModel->update($data);

        return [
            'success' => $success,
            'message' => $success ? 'Producto actualizado.' : 'Error al actualizar producto.'
        ];
    }

    public function deleteProduct(int $productId): array
    {
        $product = $this->productModel->getById($productId);

        if (!$product) {
            return ['success' => false, 'message' => 'Producto no encontrado.'];
        }

        // soft delete
        $deleted = $this->productModel->delete($productId);

        if (!$deleted) {
            return ['success' => false, 'message' => 'No se pudo desactivar el producto.'];
        }

        // no eliminamos del disco la imagen porque el borrado es un soft delete

        return ['success' => true, 'message' => 'Producto desactivado correctamente.'];

    }

    /**
     * Retorna la cantidad total de productos
     * @return int
     */
    public function getTotalProductsCount(): int
    {
        return $this->productModel->countAllProducts();
    }

    /**
     * Retorna un arreglo con los productos recientes
     *
     * @param int $limit
     * @return array
     */
    public function getRecentProducts(int $limit = 5): array
    {
        return $this->productModel->getRecentProducts($limit);
    }
    public function findDetailedById(int $id): ?array
    {
        return $this->productModel->getDetailedById($id);
    }

    // Obtiene imágenes adicionales
    public function getProductImages($productId)
    {
        return $this->productModel->getImages($productId);
    }

    // Obtiene reseñas del producto
    public function getReviews($productId)
    {
        return $this->productModel->getReviews($productId);
    }

    // Obtiene productos relacionados (por categoría)
    // establecer limite de 8 por default en los parametros del metodo
    public function getRelatedProducts($categoryId, $excludeProductId)
    {
        return $this->productModel->getRelated($categoryId, $excludeProductId);
    }

    public function getTotalSoldCount(): int
    {
        return $this->productModel->getTotalSoldCount();
    }

    // metodos para poder insertar varias imagenes del producto

    /**
     * Genera imágenes adicionales de cada producto con un watermark de texto.
     *
     * Para cada producto registrado:
     * - Verifica que exista la imagen principal.
     * - Crea hasta 4 copias con watermark en la esquina superior izquierda.
     * - Soporta formatos: jpg, jpeg, png, webp, gif.
     * - Inserta la nueva imagen en la base de datos si aún no existe.
     *
     * Nota:
     * - Maneja transparencia en PNG y WebP usando alpha.
     * - Usa funciones dinámicas según la extensión (`imagecreatefrom*` y `image*`).
     * - Libera memoria con `imagedestroy` después de cada imagen.
     *
     * @throws Exception Si ocurre algún error durante la generación de imágenes.
     */
    public function generateImagesWithWatermark() {
        try {
            $products = $this->productModel->getAllProducts();

            foreach ($products as $product) {
                $productId = $product['product_id'];
                $mainImage = $product['image'];
                if (!$mainImage) continue;

                $sourcePath = BASE_PATH . "/public/assets/images/products/$mainImage";

                if (!file_exists($sourcePath)) continue;

                $extension = strtolower(pathinfo($mainImage, PATHINFO_EXTENSION));
                $createFunc = $this->getCreateFunc($extension);
                $saveFunc = $this->getSaveFunc($extension);

                if (!$createFunc || !$saveFunc) continue;

                for ($i = 1; $i <= 4; $i++) {
                    $newImageName = "product-{$productId}-{$i}.$extension";
                    $destPath = BASE_PATH . "/public/assets/images/products/$newImageName";

                    if ($this->productModel->imageExists($productId, "$newImageName")) continue;

                    $img = @$createFunc($sourcePath);
                    if (!$img) continue;

                    $color = ($extension === 'png' || $extension === 'webp') 
                                ? imagecolorallocatealpha($img, 255, 0, 0, 80)
                                : imagecolorallocate($img, 255, 0, 0);

                    imagestring($img, 5, 10, 10, "Image $i", $color);
                    $saveFunc($img, $destPath);
                    imagedestroy($img); // liberar memoria

                    $this->productModel->insertProductImage($productId, "$newImageName");
                }
            }
        } catch(Exception $e) {
            print_r($e->getMessage());
        }
    }

    /**
     * Devuelve la función de PHP para crear un recurso de imagen en memoria
     * según la extensión proporcionada.
     *
     * Ejemplos:
     * - 'jpg'  -> 'imagecreatefromjpeg'
     * - 'png'  -> 'imagecreatefrompng'
     * - 'webp' -> 'imagecreatefromwebp'
     * - 'gif'  -> 'imagecreatefromgif'
     *
     * @param string $ext La extensión del archivo de imagen (en minúsculas).
     * @return string|null El nombre de la función GD para crear la imagen o null si no soportada.
     */
    private function getCreateFunc($ext) {
        return match($ext) {
            'jpg', 'jpeg' => 'imagecreatefromjpeg',
            'png' => 'imagecreatefrompng',
            'webp' => 'imagecreatefromwebp',
            'gif' => 'imagecreatefromgif',
            default => null
        };
    }

    /**
     * Devuelve la función de PHP para guardar un recurso de imagen en disco
     * según la extensión proporcionada.
     *
     * Ejemplos:
     * - 'jpg'  -> 'imagejpeg'
     * - 'png'  -> 'imagepng'
     * - 'webp' -> 'imagewebp'
     * - 'gif'  -> 'imagegif'
     *
     * @param string $ext La extensión del archivo de imagen (en minúsculas).
     * @return string|null El nombre de la función GD para guardar la imagen o null si no soportada.
     */
    private function getSaveFunc($ext) {
        return match($ext) {
            'jpg', 'jpeg' => 'imagejpeg',
            'png' => 'imagepng',
            'webp' => 'imagewebp',
            'gif' => 'imagegif',
            default => null
        };
    }
    // metodos para poder insertar varias imagenes del producto

}