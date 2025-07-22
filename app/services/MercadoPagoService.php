<?php
namespace App\Services;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use App\Helpers\SecureLogger;

class MercadoPagoService
{

    public function __construct()
    {
        $config = require __DIR__ . '/../config/mercadopago.php';
        MercadoPagoConfig::setAccessToken($config['access_token']);
    }


    public function createPreference(array $cartItems, $orderId)
    {
        $logger = new SecureLogger();
        try {
            $mpItems = $this->mapCartItemsToMpItems($cartItems);
            $config = require __DIR__ . '/../config/mercadopago.php';
            $backUrl = $config['back_url'];
            $client = new PreferenceClient();
            $preference = $client->create([
                "items" => $mpItems,
                "back_urls" => [
                    "success" => $backUrl,
                    "failure" => $backUrl,
                    "pending" => $backUrl
                ],
                "auto_return" => "approved",
                "external_reference" => (string)$orderId,
                "metadata" => [
                    "order_id" => $orderId
                ],
            ]);
            return $preference;
        } catch (\Exception $e) {
            $logger->write('Cart Items', ['items' => $cartItems]);
            $logger->write('Mapped MP Items', ['items' => $mpItems]);
            $logger->write('Back URL', ['url' => $backUrl]);
            $logger->write('Excepción del preference client', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Transforma items del carrito a formato MercadoPago
     * @param array $items
     * @return array
     */
    private function mapCartItemsToMpItems(array $items): array
    {
        return array_values(array_map(
            fn($item)=> [
                'title'       => $item['name'],
                'quantity'    => (int)$item['qty'],
                'unit_price'  => (float)$item['price']
            ],
            $items
        ));
    }
}
