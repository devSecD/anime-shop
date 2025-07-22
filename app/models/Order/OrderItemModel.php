<?php
namespace Models\Order;

use PDO;

class OrderItemModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function createMany(int $orderId, array $items): void
    {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (:order_id, :product_id, :quantity, :price)";

        $stmt = $this->db->prepare($sql);

        foreach ($items as $item) {
            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', (int)$item['product_id'], PDO::PARAM_INT);
            $stmt->bindValue(':quantity', (int)$item['qty'], PDO::PARAM_INT);
            $stmt->bindValue(':price', (float)$item['price']);
            $stmt->execute();
        }
    }
}