<?php
namespace Models\Order;

use PDO;

class OrderModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(int $userId, float $total, ?string $preferenceId, int $shippingAddressId, string $status = 'pending'): int
    {
        $sql = "INSERT INTO orders (user_id, total, preference_id, shipping_address_id, status) 
                VALUES (:user_id, :total, :preference_id, :shipping_address_id, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':total', $total);
        $stmt->bindValue(':preference_id', $preferenceId);
        $stmt->bindValue(':shipping_address_id', $shippingAddressId);
        $stmt->bindValue(':status', $status);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function updatePreferenceId(int $orderId, string $preferenceId): bool
    {
        $sql = "UPDATE orders SET preference_id = :preference_id WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':preference_id', $preferenceId, \PDO::PARAM_STR);
        $stmt->bindValue(':order_id', $orderId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        $sql = "UPDATE orders SET status = :status WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function getOrderById(int $orderId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_id = :order_id");
        $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ?: null;
    }

    public function getOrderItems(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT oi.product_id, oi.quantity, oi.price, p.name, p.image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.product_id
            WHERE oi.order_id = :order_id
        ");
        $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedWithUser(int $limit, int $offset): array
    {
        $sql = "SELECT o.*, u.name AS user_name, u.email AS user_email
                FROM orders o
                INNER JOIN users u ON o.user_id = u.user_id
                ORDER BY o.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) FROM orders";
        return (int) $this->db->query($sql)->fetchColumn();
    }

}