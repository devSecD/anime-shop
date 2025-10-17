<?php
namespace Models\Order;

class OrderRepository
{
    private OrderModel $model;

    public function __construct(OrderModel $model)
    {
        $this->model = $model;
    }

    public function createOrder(int $userId, float $total, ?string $preferenceId, int $shippingAddressId, string $status = 'pending'): int
    {
        // de acuerdo al modelo de ordenes retorna el id de la orden que se creo
        return $this->model->create($userId, $total, $preferenceId, $shippingAddressId, $status);
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        return $this->model->updateStatus($orderId, $status);
    }

    public function updatePreferenceId(int $orderId, string $preferenceId): bool
    {
        return $this->model->updatePreferenceId($orderId, $preferenceId);
    }

    public function getOrderWithItems(int $orderId): ?array
    {
        $order = $this->model->getOrderById($orderId);
        if (!$order) return null;

        $items = $this->model->getOrderItems($orderId);
        $order['items'] = $items;

        return $order;
    }

    public function exists(int $orderId): bool
    {
        return $this->model->getOrderById($orderId) !== null;
    }

    public function getPaginatedOrders(int $limit, int $offset, ?string $status = null): array
    {
        $sql = "SELECT o.*, u.name AS user_name, u.email AS user_email
                FROM orders o
                INNER JOIN users u ON o.user_id = u.user_id WHERE 1=1"; // '1=1' para poder concatenar AND fácilmente
        $params = [];

        if ($status) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        // Se delega la ejecución al modelo. Devuelve un arreglo asociativo
        return $this->model->executeQuery($sql, $params);
    }


    public function countOrders(?string $status = null): int
    {
        return $status === 'pending'
            ? $this->model->countPendingOrders()
            : $this->model->countAll();       
    }

    public function getUserOrders(int $userId, int $limit = 5, int $offset = 0): array
    {
        // Devuelve un arreglo de órdenes de un usuario, con paginación controlada por $limit y $offset
        return $this->model->getOrdersByUser($userId, $limit, $offset);
    }

    public function countUserOrders(int $userId): int
    {
        return $this->model->countOrdersByUser($userId);
    }

}