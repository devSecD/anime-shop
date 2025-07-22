<?php
namespace Models\PaymentLog;

class PaymentLogRepository {
    private PaymentLogModel $model;

    public function __construct(PaymentLogModel $model) {
        $this->model = $model;
    }

    public function createLog($orderId, $eventType, $payload) {
        return $this->model->insertLog($orderId, $eventType, $payload);
    }
}
