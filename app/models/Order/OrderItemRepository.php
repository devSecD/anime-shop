<?php
namespace Models\Order;

class OrderItemRepository
{
    private OrderItemModel $model;

    public function __construct(OrderItemModel $model)
    {
        $this->model = $model;
    }

    public function createItems(int $orderId, array $items): void
    {
        $this->model->createMany($orderId, $items);
    }
}