<?php
namespace Models\User;

use PDO;

class PasswordResetRepository
{
    private $model;

    public function __construct(PDO $db)
    {
        $this->model = new PasswordResetModel($db);
    }

    public function storeToken(int $userId, string $plainToken, string $expiresAt): bool
    {
        $tokenHash = hash('sha256', $plainToken);

        // opcional: eliminar tokens previos
        $this->model->deleteAllByUser($userId);

        return $this->model->create([
            'user_id' => $userId, 
            'token_hash' => $tokenHash, 
            'expires_at' => $expiresAt
        ]);
    }

    public function verifyToken(int $userId, string $plainToken): bool
    {
        $tokenHash = hash('sha256', $plainToken);
        $record = $this->model->getValidToken($userId, $tokenHash);
        return $record !== null;
    }

    public function consumeToken(int $userId, string $plainToken): bool
    {
        $tokenHash = hash('sha256', $plainToken);
        return $this->model->deleteToken($userId, $tokenHash);
    }

}