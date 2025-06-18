<?php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    protected $mailer;

    public function __construct()
    {
        // Incluir manualmente los archivos de PHPMailer
        require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
        require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';
        require_once __DIR__ . '/../libs/PHPMailer/Exception.php';

        // Cargar configuración
        $config = require __DIR__ . '/../config/email.php';

        $this->mailer = new PHPMailer(true);

        try {
            // Configuración básica SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $config['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $config['username'];
            $this->mailer->Password = $config['password'];
            $this->mailer->SMTPSecure = $config['secure'];
            $this->mailer->Port = $config['port'];

            // Remitente por defecto
            $this->mailer->setFrom($config['from_email'], $config['from_name']);
            $this->mailer->isHTML(true);
        } catch (Exception $e) {
            // En caso de error, podés registrar o lanzar
            throw new \Exception("Error al configurar el correo: " . $e->getMessage());
        }
    }

    public function send(string $to, string $subject, string $body): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            $this->mailer->Subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
            $this->mailer->Body = $body;

            return $this->mailer->send();
        } catch (Exception $e) {
            // Podés loguear el error si querés
            error_log("Error al enviar correo: " . $e->getMessage());
            return false;
        }
    }
}
