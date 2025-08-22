<?php
namespace App\Models\Wishlist;

use PDO;

class WishlistModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Agregar producto al wishlist
     */
    public function addItem(int $userId, int $productId): bool
    {
        $sql = "INSERT INTO wishlist (user_id, product_id, created_at)
                VALUES (:user_id, :product_id, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Eliminar producto del wishlist
     */
    public function removeItem(int $userId, int $productId): bool
    {
        $sql = "DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Verificar si un producto ya está en el wishlist
     */
    public function exists(int $userId, int $productId): bool
    {
        $sql = "SELECT COUNT(*) FROM wishlist WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Obtener todos los productos del wishlist de un usuario
     */
    public function getByUser(int $userId): array
    {
        $sql = "SELECT w.product_id, p.name, p.price, p.image
                FROM wishlist w
                JOIN products p ON w.product_id = p.product_id
                WHERE w.user_id = :user_id
                ORDER BY w.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
