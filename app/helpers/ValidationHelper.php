<?php
namespace App\Helpers;
use Models\Product\ProductRepository;

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

    /**
     * Valida que un número entero sea mayor o igual a 1.
     * 
     * @param string $fieldName Nombre del campo para mensaje de error
     * @param int|float|null $value valor a validar
     * @return string|null Mensaje de error o null si es válido
     */
    public static function mustBePositiveInt(string $fieldName, $value): ?string
    {
        if (!is_numeric($value) || (int)$value < 1  ) return "El campo {$fieldName} debe ser un número entero mayor o igual a 1";
        
        return null;
    }   

    /**
     * Valida la existencia de un recurso basado en su entidad y su id
     * 
     * @param callable $finderFunc Función que recibe el id y devuelve el recurso o null si no existe
     * @param int $id Identificador del recurso
     * @param string $resourceName Nombre del recurso para mensaje de error (ej. "producto")
     * @return string|null Mensaj de error o null si existe
     */
    public static function mustExist(callable $finderFunc, int $id, string $resourceName): ? string
    {
        $resource = $finderFunc($id);

        if (!$resource) return "El {$resourceName} espeficado no existe.";

        return null;
    }
    /**
     * Valida que un número no exceda un maximo disponible.
     * 
     * @param string $fieldName Nombre del campo para mensaje
     * @param int|float $value Valor a validar
     * @param int|float $maxValue Valor máximo permitido
     * @return string|null Mensaje de error o null si es válido
     */
    public static function mustNotExceed(string $fieldName, $value, $maxValue): ?string
    {
        if ($value > $maxValue) return "El campo {$fieldName} no puede ser mayor que {$maxValue}.";

        return null;
    }

    /* metodo para validar stock */
    public static function validateStock(ProductRepository $repo, int $productId, ?int $quantity): ?string
    {
        // 1. Validar cantidad positiva
        $error = self::mustBePositiveInt('cantidad', $quantity);
        if ($error) return $error;

        // 2. Validar existencia del producto
        $error = self::mustExist(   
        fn($id) => $repo->findById($id),
            $productId, 
            'producto' 
        );
        if ($error) return $error;

        // 3. Validar que cantidad no exceda stock
        $product = $repo->findById($productId);
        $stock = (int)($product['stock'] ?? 0); 

        return self::mustNotExceed('cantidad', $quantity, $stock);
    }
    /* metodo para validar stock */

    // Valida teléfono (solo dígitos, entre 10 y 15)
    public static function validatePhone(?string $value): ?string 
    {
        $value = StringHelper::trim($value);

        if (!preg_match('/^\d{10,15}$/', $value)) {
            return "El teléfono debe tener entre 10 y 15 dígitos.";
        }

        return null;
    }

    // Valida código postal de manera genérica
    public static function validatePostalCode(?string $value): ?string
    {
        $value = StringHelper::trim($value);

        if (!preg_match('/^[A-Za-z0-9\- ]{3,10}$/', $value)) {
            return "El código postal no tiene un formato válido.";
        }

        return null;
    }

    /**
     * Valida que un número (entero o flotante) sea positivo (> 0).
     * 
     * @param int|float|null $n
     * @return string|null
     */
    public static function validatePositive($n): ?string
    {
        if (!is_numeric($n) || $n <= 0) return "Debe ser mayor a 0.";
        return null;
    }

    public static function validatePaymentMethod(callable $finderFunc, int $id): ?string
    {
        return self::mustExist($finderFunc, $id, 'método de pago');
    }

    public static function validateShippingAddress(callable $finderFunc, int $id): ?string
    {
        return self::mustExist($finderFunc, $id, 'dirección de envío');
    }

    /**
     * Valida que se haya subido un archivo en un campo requerido.
     * 
     * @param array|null $file El archivo de $_FILES['campo']
     * @param string $fieldName Nombre del campo para el mensaje
     * @return string|null Mensaje de error o null si es válido
     */
    public static function validateRequiredFile(?array $file, string $fieldName): ?string
    {
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return "La imagen del producto es obligatoria.";
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Error al subir el archivo para el campo {$fieldName}.";
        }

        return null;
    }

    /**
     * Valida que un valor opcional sea un booleano (0 o 1).
     * 
     * @param mixed $value
     * @param string $fieldName
     * @return string|null
     */
    public static function mustBeOptionalBoolean($value, string $fieldName): ?string
    {
        if ($value === null || $value === '') return null;

        // Aceptamos 0, 1, "0", "1"
        if (!in_array($value, [0, 1, '0', '1'], true)) {
            return "El campo {$fieldName} debe ser verdadero o falso.";
        }

        return null;
    }

    // Valida que el archivo subido sea una imagen válida y segura
    public static function validateImageUpload(?array $file, string $fieldName = 'imagen'): ?string
    {
        // Validación básica (ya la tienes pero la reusamos)
        $error = self::validateRequiredFile($file, $fieldName);
        if ($error) return $error;

        // Validar tamaño (ej. max 5 MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return "La imagen excede el tamaño máximo permitido (5 MB).";
        }

        // Validar extensión segura (evitar .php, .exe, etc.)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            return "La extensión del archivo no está permitida.";
        }

        // Validar tipo MIME real (no solo por extensión)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
            return "El archivo no es una imagen válida.";
        }

        // Validar que realmente sea una imagen (dimensiones válidas)
        $imageSize = getimagesize($file['tmp_name']);
        if ($imageSize === false) {
            return "El archivo no contiene datos de imagen válidos.";
        }

        return null; // todo bien
    }

    /**
     * Valida que el precio con descuento no exceda el precio normal.
     *
     * @param float|null $discounted Precio con descuento
     * @param float|null $regular Precio normal
     * @return string|null Mensaje de error o null si es válido
     */
    public static function validateDiscountedPrice(?float $discounted, ?float $regular): ?string
    {
        if (!is_null($discounted) && $discounted > $regular) {
            return "El precio con descuento no puede ser mayor que el precio normal.";
        }
        return null;
    }

    /**
     * Valida que el timezone sea válido según PHP
     */
    public static function validateTimezone(?string $value): ?string
    {
        if (!in_array($value, timezone_identifiers_list(), true)) {
            return "La zona horaria no es válida.";
        }
        return null;
    }

    /* metodo exclusivo para validaciones de setting del panel administrativo de la tienda */
    public static function validateSetting(string $key, ?string $value): ?string
    {
        switch ($key) {
            case 'site_name':
                return self::validateName($value);

            case 'contact_email':
                return self::validateEmail($value);

            case 'items_per_page':
                return self::mustBePositiveInt('ítems por página', $value);

            case 'timezone':
                return self::validateTimezone($value);

            case 'maintenance_mode':
                return self::mustBeOptionalBoolean($value, 'modo mantenimiento');

            default:
                return self::required($key, $value);
        }
    }

}