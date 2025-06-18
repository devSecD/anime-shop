<?php
namespace Models\User;

use PDO;
use PDOException;

class UserModel
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByEmail($email): array
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? : [];
    }

    public function createUser(array $data): bool
    {
        try {
            $sql = "INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $data['password'], PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ((int)$e->getCode() === 23000) {
                return false;
            }
            throw $e;
        }
    }

    public function updatePassword(int $userId, string $hashedPassword): bool
    {
        $sql = "UPDATE users SET password_hash = :password WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue('id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function clearResetToken($userId): bool
    {
        $sql = "UPDATE users SET reset_token = NULL WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById(int $id): array
    {
        $sql = "SELECT * FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

}