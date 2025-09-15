<?php
namespace App\Helpers;

class OrderHelper
{
    /**
     * Retorna un texto amigable para el estado de la orden
     * @param string $status
     * @return string
     */
    public static function getStatusText(string $status): string
    {
        return match ($status) {
            'cancelled' => 'Productos (Pedido cancelado)',
            'rejected'  => 'Productos (Pedido rechazado)',
            'pending'   => 'Productos (Pendiente de pago)',
            default     => 'Productos comprados',
        };
    }

    /**
     * Devuelve la dirección de envío completa en un formato amigable
     * @param array $order
     * @return string
     */
    public static function getFullShippingAddress(array $order): string
    {
        $parts = [];

        if (!empty($order['fullname'])) {
            $parts[] = htmlspecialchars($order['fullname']);
        }
        if (!empty($order['street'])) {
            $parts[] = htmlspecialchars($order['street']);
        }
        if (!empty($order['neighborhood'])) {
            $parts[] = htmlspecialchars($order['neighborhood']);
        }
        if (!empty($order['city'])) {
            $parts[] = htmlspecialchars($order['city']);
        }
        if (!empty($order['state'])) {
            $parts[] = htmlspecialchars($order['state']);
        }
        if (!empty($order['postal_code'])) {
            $parts[] = htmlspecialchars($order['postal_code']);
        }
        if (!empty($order['country'])) {
            $parts[] = htmlspecialchars($order['country']);
        }

        return implode(', ', $parts);
    }
}
