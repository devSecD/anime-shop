<?php
namespace Models\User;

use App\Helpers\SecureLogger;

use PDO;
use PDOException;

class UserModel
{
    protected $db;
    private SecureLogger $logger;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->logger = new SecureLogger('user.log');
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

    public function getRoleIdByName(string $roleName): ?int
    {
        $sql = "SELECT role_id FROM roles WHERE role_name = :role_name LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role_name', $roleName, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['role_id'] : null;
    }

    public function assignRoleToUser(int $userId, int $roleId): bool
    {
        $sql = "INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':role_id', $roleId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createUser(array $data): int|false
    {
        try {
            $sql = "INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, :role)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $data['password'], PDO::PARAM_STR);
            $stmt->bindValue(':role', $data['role'], PDO::PARAM_STR);

            $stmt->execute();

            $lastId = (int) $this->db->lastInsertId();
            return $lastId > 0 ? $lastId : false;
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

    public function getUserRoles(int $userId): array 
    {
        $sql = "
            SELECT r.role_name 
            FROM roles r 
            INNER JOIN user_roles ur ON r.role_id = ur.role_id 
            WHERE ur.user_id = :user_id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAll(): array
    {
        $sql = "SELECT 
                    u.user_id,
                    u.name,
                    u.email,
                    u.phone,
                    u.role,
                    u.created_at,
                    GROUP_CONCAT(r.role_name SEPARATOR ', ') AS roles
                FROM users u
                LEFT JOIN user_roles ur ON u.user_id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.role_id
                GROUP BY u.user_id
                ORDER BY u.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (PDOException $e) {
            $this->logger->write('Error en findByEmail', ['email' => $email, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function update(int $userId, array $data): bool
    {
        try {
            $sql = "UPDATE users SET name = :name, email = :email, phone = :phone";
            if (!empty($data['password_hash'])) {
                $sql .= ", password_hash = :password_hash";
            }
            $sql .= " WHERE user_id = :user_id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':phone', $data['phone'] ?? null);
            if (!empty($data['password_hash'])) {
                $stmt->bindValue(':password_hash', $data['password_hash']);
            }
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logger->write('Error en update', [
                'user_id' => $userId,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

}