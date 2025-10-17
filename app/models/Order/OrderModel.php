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
        $stmt = $this->db->prepare("
            SELECT o.*, u.name AS user_name, u.email AS user_email, sa.address_id, sa.fullname, 
            sa.email AS sa_email, sa.phone AS sa_phone, sa.street, sa.neighborhood, sa.postal_code, 
            sa.city, sa.state, sa.country, 
            JSON_UNQUOTE(JSON_EXTRACT(pl.payload, '$.status_original')) AS status_original 
            FROM orders o
            INNER JOIN users u ON o.user_id = u.user_id 
            INNER JOIN shipping_addresses sa ON sa.address_id = o.shipping_address_id 
            LEFT JOIN payment_logs pl ON pl.order_id = o.order_id
            WHERE o.order_id = :order_id
        ");
        $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ?: null;
    }

    public function getOrderItems(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT oi.product_id, oi.quantity, oi.price, p.name, p.image, p.stock
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.product_id
            WHERE oi.order_id = :order_id
        ");
        $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ejecuta un query con parámetros y devuelve el resultado como array asociativo.
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function executeQuery(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            // Detectar el tipo de parámetro
            if (is_int($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna el total de órdenes
     * @return int
     */
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM orders";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }

    /**
     * Retorna la cantidad de órdenes en estado 'pending' o 'paid' (En espera)
     * @return int
     */
    public function countPendingOrders(): int
    {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status IN ('pending')"; // WHERE status IN ('pending', 'paid' para mejora de la version 2
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }

    public function countOrdersByUser(int $userId): int
    {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }

    public function getOrdersByUser(int $userId, int $limit = 5, int $offset = 0): array
    {
        $sql = "SELECT * FROM orders 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}