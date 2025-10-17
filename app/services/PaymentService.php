<?php
namespace App\Services;

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use App\Helpers\SecureLogger;

class PaymentService
{
    private $accessToken;
    private $collectorId;

    public function __construct(array $config)
    {
        $this->accessToken = $config['access_token'];
        $this->collectorId = $config['collector_id'];
        MercadoPagoConfig::setAccessToken($this->accessToken);
    }

     // Verifica si el pago pertenece a mi cuenta (collector_id)
    public function isPaymentFromMyCollector($payment): bool
    {
        $logger = new SecureLogger();
        if ($payment->collector_id !== $this->collectorId) {
            $logger->write('Payment no pertenece a mi collector', [
                'received_collector_id' => $payment->collector_id,
                'expected_collector_id' => $this->collectorId
            ]);
            return false;
        }
        return true;
    }

     // Verifica si el pago es válido: aprobado y de mi cuenta
    public function isPaymentValid($payment): bool
    {
        $logger = new SecureLogger();
        if ($payment->status !== 'approved') {
            $logger->write('Payment no aprobado', ['status' => $payment->status]);
            return false;
        }
        if (!$this->isPaymentFromMyCollector($payment)) {
            $logger->write('Payment no es de mi collector en isPaymentValid', []);
            return false;
        }
        return true;
    }

     // Mapea status de Mercado Pago a status local
    public function mapPaymentStatus($payment): ?string
    {
        $logger = new SecureLogger();

        if (!$this->isPaymentFromMyCollector($payment)) {
            $logger->write('Payment no es de mi collector en mapPaymentStatus', [
                'received_collector_id' => $payment->collector_id
            ]);
            return null;
        }

        switch ($payment->status) {
            case 'approved':
                return 'paid';
            case 'rejected':
                return 'cancelled';
            case 'in_process':
                return 'pending';
            default:
                $logger->write('Status desconocido en mapPaymentStatus', [
                    'status' => $payment->status
                ]);
                return null;
        }
    }

    public function getPaymentById(int $paymentId)
    {
        $logger = new SecureLogger();
        try {
            $client = new PaymentClient();
            return $client->get($paymentId);
            // Retorna un objeto Payment del SDK de Mercado Pago con la información del pago.
            // En caso de error (capturado por el try/catch), el método retornará null.
        } catch (\Exception $e) {
            $logger->write('Excepción al consultar payment', ['error' => $e->getMessage()]);
            return null;
        }
    }
}