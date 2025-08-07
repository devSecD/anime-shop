<?php
namespace App\Services;

class UploadService
{
    public function upload(array $file, string $subfolder): ?string
    {
        // Validación mínima del archivo
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        // Obtener la extensión segura
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newName = 'img_' . str_replace('.', '', uniqid('', true)) . '.' . $ext;

        // Ruta absoluta a la carpeta de uploads
        $uploadDir = dirname(__DIR__, 2) . "/public/assets/images/{$subfolder}/";

        // Crear carpeta si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Ruta destino completa
        $destination = $uploadDir . $newName;

        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $newName; // solo guardas el nombre en la BD
        }

        return null;
    }
}
