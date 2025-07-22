<?php
namespace Models\PaymentLog;

use PDO;

class PaymentLogModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function insertLog($orderId, $eventType, $payload) {
        $stmt = $this->db->prepare("
            INSERT INTO payment_logs (order_id, event_type, payload)
            VALUES (:order_id, :event_type, :payload)
        ");
        $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->bindValue(':event_type', $eventType, PDO::PARAM_STR);
        $stmt->bindValue(':payload', json_encode($payload), PDO::PARAM_STR);
        return $stmt->execute();
    }
}
