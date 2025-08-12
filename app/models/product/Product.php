<?php
namespace Models\Product;

use Exception;
use PDO;
use PDOException;

class Product
{
    protected $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function create(array $data)
    {
        $sql = "INSERT INTO products 
            (name, description, price, price_discounted, stock, category_id, brand_id, image, is_on_sale, is_preorder) 
            VALUES 
            (:name, :description, :price, :price_discounted, :stock, :category_id, :brand_id, :image, :is_on_sale, :is_preorder)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_STR);
        $stmt->bindValue(':price_discounted', $data['price_discounted'], is_null($data['price_discounted']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':stock', $data['stock'], PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindValue(':brand_id', $data['brand_id'], PDO::PARAM_INT);
        $stmt->bindValue(':image', $data['image'], PDO::PARAM_STR);
        $stmt->bindValue(':is_on_sale', $data['is_on_sale'], PDO::PARAM_INT);
        $stmt->bindValue(':is_preorder', $data['is_preorder'], PDO::PARAM_INT);

        // Ejecutar con bind de datos
        $result = $stmt->execute();

        if ($result) {
            return $this->db->lastInsertId();
        }

        return false;
    }
    /**
     * Devuelve un producto por ID
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM products WHERE product_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: null;
    }

    public function getByIds(array $ids, string $in): array
    {
        $sql = "SELECT * FROM products WHERE product_id IN($in)";
        $stmt = $this->db->prepare($sql);

        foreach($ids as $idx => $id) {
            $stmt->bindValue($idx + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeQuery($sql, $params)
    {
        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener productos filtrados: " . $e->getMessage());
        }
    }

    public function executeCount($sql, $params)
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
    public function interpolateNamedQuery($query, $params)
    {
        foreach ($params as $key => $value) {
            $quoted = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $query = str_replace($key, $quoted, $query);
        }
        return $query;
    }

    public function decreaseStock(int $productId, int $qty): bool
    {
        $sql = "UPDATE products SET stock = stock - :qty WHERE product_id = :productId AND stock >= :qty";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':qty', $qty, \PDO::PARAM_INT);
        $stmt->bindValue(':productId', $productId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createProduct(array $data): bool 
    {
        $sql = "INSERT INTO products (name, description, price, stock, category_id, brand_id) 
                VALUES (:name, :description, :price, :stock, :category_id, :brand_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_STR); // definir que pddo:: sera porque es un valor decimal/flotante
        $stmt->bindValue(':stock', $data['stock'], PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindValue(':brand_id', $data['brand_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update(array $data): bool
    {
        $sql = "UPDATE products SET 
                    name = :name,
                    description = :description,
                    price = :price,
                    price_discounted = :price_discounted,
                    stock = :stock,
                    image = :image,
                    category_id = :category_id,
                    brand_id = :brand_id,
                    is_on_sale = :is_on_sale,
                    is_preorder = :is_preorder
                WHERE product_id = :product_id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_STR);
        $stmt->bindValue(':price_discounted', $data['price_discounted'], is_null($data['price_discounted']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':stock', $data['stock'], PDO::PARAM_INT);
        $stmt->bindValue(':image', $data['image'], PDO::PARAM_STR);
        $stmt->bindValue(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindValue(':brand_id', $data['brand_id'], PDO::PARAM_INT);
        $stmt->bindValue(':is_on_sale', $data['is_on_sale'], PDO::PARAM_INT);
        $stmt->bindValue(':is_preorder', $data['is_preorder'], PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $data['product_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE product_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    /**
     * Obtiene el total de productos en la base de datos
     * @return int
     */
    public function countAllProducts(): int
    {
        $sql = "SELECT COUNT(*) as total FROM products";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }
    /**
     * Obtiene los productos más recientes limitados
     * 
     * @param int $limit Cantidad máxima de productos a obtener
     * @return array
     */
    public function getRecentProducts(int $limit = 5): array
    {
        $sql = "SELECT product_id, name, price, stock FROM products ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}