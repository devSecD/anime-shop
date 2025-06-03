<?php
namespace App\Helpers;

class ValidationHelper
{
    public static function isPostRequest(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function validateEmail(?string $email): bool
    {
        return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function rejectIfNotPost(): void
    {
        if (!self::isPostRequest()) {
            http_response_code(405);
            echo "Metodo no permitido";
            exit;
        }
    }
}