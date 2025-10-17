<?php
namespace Models\Newsletter;

use PDO;
use PDOException;

class NewsletterModel
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

     // Inserta una nueva subscripcion al newsletter
    public function subscribe($email)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO newsletter_subscriptions (email) VALUES (:email)");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            // si ya existe el email (clave primaria), se ignora
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

     // Verifica si un email ya esta suscrito
    public function exists($email)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM newsletter_subscriptions WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getPaginated(int $limit, int $offset): array
    {
        $sql = "SELECT * FROM newsletter_subscriptions 
                ORDER BY subscribed_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById(string $email): bool
    {
        $sql = "DELETE FROM newsletter_subscriptions WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':email' => $email]);
    }

    /**
     * Cuenta total de suscriptores activos
     * @return int
     */
    public function countSubscribers(): int
    {
        $sql = "SELECT COUNT(*) as total FROM newsletter_subscriptions";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }

    public function deleteByEmail(string $email): bool
    {
        $sql = "DELETE FROM newsletter_subscriptions WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':email' => $email]);
    }

}