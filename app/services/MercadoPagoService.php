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

    /**
     * Crea una preferencia de pago en Mercado Pago a partir de los items del carrito y una orden.
     *
     * @param array $cartItems Lista de productos del carrito. Cada elemento debe incluir información necesaria
     *                         como título, cantidad y precio para mapearlos a items de Mercado Pago.
     * @param int|string $orderId Identificador único de la orden en el sistema local.
     *
     * @return \MercadoPago\Resources\Preference|null Objeto Preference creado por Mercado Pago.
     *                                               Retorna null si ocurre un error durante la creación.
     *
     * @throws \MercadoPago\Exceptions\MPApiException Si la petición a la API de Mercado Pago falla.
     * @throws \Exception Si ocurre un error genérico en el proceso.
     *
     * Flujo de trabajo:
     * 1. Mapea los items del carrito a formato compatible con Mercado Pago.
     * 2. Obtiene configuración desde `mercadopago.php` (ej. back_url).
     * 3. Instancia un cliente `PreferenceClient`.
     * 4. Envía la solicitud de creación con datos:
     *    - items
     *    - back_urls (success, failure, pending)
     *    - auto_return = approved
     *    - external_reference = orderId
     *    - metadata con order_id
     * 5. Retorna el objeto Preference resultante o null en caso de error.
     */
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
                "auto_return" => "approved", // el comprador vuelve automáticamente al sitio después de pagar.
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
