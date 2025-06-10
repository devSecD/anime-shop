<?php
namespace App\Helpers;

use App\Helpers\StringHelper;

// clase general para validaciones
class ValidationHelper
{
    public static function isPostRequest(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function rejectIfNotPost(): void
    {
        if (!self::isPostRequest()) {
            http_response_code(405);
            echo "Metodo no permitido";
            exit;
        }
    }

    public static function required(string $fieldName, ?string $value): ?string
    {
        return empty(StringHelper::trim($value)) ? "El campo $fieldName es obligatorio." : null ;
    }

    public static function validateName(?string $value): ?string
    {
        $value = StringHelper::trim($value);

        if (strlen($value) < 3) {
            return "El nombre debe tener al menos 3 caracteres.";
        }

        if (!preg_match('/^[\p{L}\s]+$/u', $value)) {
            return "El nombre solo puede contener letras y espacios.";
        }

        return null;
    }

    public static function validateEmail(?string $value): ?string
    {
        $value = StringHelper::trim($value);

        return filter_var($value, FILTER_VALIDATE_EMAIL) ? null : "El correo no es válido." ;
    }

    public static function validatePasswordStrength(?string $value): ?string
    {
        $value = StringHelper::trim($value);

        if (strlen($value) < 8) {
            return "La contraseña debe tener al menos 8 caracteres.";
        }

        if (!preg_match('/[A-Za-z]/', $value) || !preg_match('/\d/', $value)) {
            return "La contraseña debe contener letras y números.";
        }

        return null;
    }

    public static function matchPasswords(?string $pass, ?string $confirm): ?string
    {
        $pass = StringHelper::trim($pass);
        $confirm = StringHelper::trim($confirm);
        return $pass !== $confirm ? "Las contraseñas no coinciden." : null ;
    }

}