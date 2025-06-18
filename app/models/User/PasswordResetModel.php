<?php
namespace Models\User;

use PDO;
use PDOException;

class PasswordResetModel
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO password_resets (user_id, token_hash, expires_at, created_at) VALUES (:user_id, :token_hash, :expires_at, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':token_hash', $data['token_hash'], PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', $data['expires_at'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getValidToken(int $userId, string $tokenHash): ?array
    {
        $sql = "SELECT * FROM password_resets WHERE user_id = :user_id AND token_hash = :token_hash AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':token_hash', $tokenHash, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            return $result;
        }
        return null;
    }

    public function deleteAllByUser(int $userId): bool
    {
        $sql = "DELETE FROM password_resets WHERE user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteToken(int $userId, string $tokenHash): bool
    {
        $sql = "DELETE FROM password_resets WHERE user_id = :user_id AND token_hash = :token_hash";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':token_hash', $tokenHash, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
}