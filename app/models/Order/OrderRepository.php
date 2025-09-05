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

    public function getPaginatedOrders(int $limit, int $offset): array
    {
        return $this->model->getPaginatedWithUser($limit, $offset);
    }

    public function countOrders(): int
    {
        return $this->model->countAll();
    }

    public function getTotalOrdersCount(): int
    {
        return $this->model->countAll();
    }

    public function getPendingOrdersCount(): int
    {
        return $this->model->countPendingOrders();
    }

    public function getUserOrders(int $userId, int $limit = 5): array
    {
        return $this->model->getOrdersByUser($userId, $limit);
    }

}