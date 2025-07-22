<?php
namespace App\Helpers;

class SecureLogger
{
    private $logFile;

    public function __construct($filename = 'logs/webhook.log')
    {
        // Define ruta segura fuera de public
        $this->logFile = dirname(__DIR__, 2) . '/storage/' . $filename;

        // Si el archivo no existe, créalo con permisos restrictivos
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
            chmod($this->logFile, 0640);
        }
    }

    public function write($message, array $context = [])
    {
        // Timestamp
        $date = date('Y-m-d H:i:s');

        // Limpiar datos potencialmente peligrosos del contexto
        $contextSafe = json_encode($this->sanitize($context), JSON_UNESCAPED_UNICODE);

        // Formato de log
        $logEntry = "[$date] $message | Context: $contextSafe" . PHP_EOL;

        // Append
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    private function sanitize(array $data)
    {
        // Evitar que se registren datos sensibles como tokens
        unset($data['access_token'], $data['password'], $data['card_number']);
        return $data;
    }
}