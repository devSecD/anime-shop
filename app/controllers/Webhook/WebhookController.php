<?php

namespace Controllers\Webhook;

use Core\Controller;

use Models\PaymentLog\PaymentLogModel;
use Models\PaymentLog\PaymentLogRepository;
use App\Services\PaymentService;
use App\Helpers\ResponseHelper;
use Models\Order\OrderModel;
use Models\Order\OrderRepository;

use App\Helpers\SecureLogger;

class WebhookController extends Controller
{
    public function handle() {
        // Mercado Pago envía JSON
        $input = file_get_contents('php://input');
        $payload = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($payload)) {
            ResponseHelper::jsonResponse(['error' => 'Invalid JSON'], 400);
        }

        // Logger después de recibir payload
        $logger = new SecureLogger();

        $eventType = $payload['type'] ?? 'unknown';
        $paymentId = $payload['data']['id'] ?? null;

        // $logger->write('📦 ID de pago recibido (test manual)', ['paymentId' => $paymentId]);

        $config = require __DIR__ . '/../../config/mercadopago.php';
        $paymentService = new PaymentService($config);

        $paymentValid = false;
        $orderId = null;

        if ($paymentId) {

            $paymentInfo = $paymentService->getPaymentById((int)$paymentId);

            if ($paymentInfo && $paymentService->isPaymentFromMyCollector($paymentInfo)) {

                // Extraemos order_id que mandamos en external_reference
                $orderId = (int)($paymentInfo->external_reference ?? 0);

                // mapeamos el status que devuelve MercadoPago a nuestro status local
                $localStatus = $paymentService->mapPaymentStatus($paymentInfo);

                if ($orderId && $localStatus) {
                    $db = $this->loadDB();
                    $orderModel = new OrderModel($db);
                    $orderRepo = new OrderRepository($orderModel);

                    // Verificar si la orden existe antes de actualizar
                    if (!$orderRepo->exists($orderId)) {
                        $logger->write('❌ Orden no encontrada para actualizar estado', ['id_orden' => $orderId]);
                    } else {
                        $orderRepo->updateStatus($orderId, $localStatus);

                        // solo marcamos como válido si es approved
                        if ($localStatus === 'paid') {
                            $paymentValid = true;
                        }
                    }
                }
            }
        }

        // Guardar log (siempre)
        $db = $this->loadDB();
        $logModel = new PaymentLogModel($db);
        $logRepo = new PaymentLogRepository($logModel);

        $logRepo->createLog($orderId, $eventType, [
            'payload' => $payload,
            'payment_valid' => $paymentValid, 
            'status_original' => $paymentInfo->status ?? null, // Estado que devuelve Mercado Pago
            'status_local' => $localStatus,
        ]);

        // Responder a Mercado Pago (IMPORTANTE: status 200 para que no reintenten)
        ResponseHelper::jsonResponse(['status' => 'ok']);
    }

}
