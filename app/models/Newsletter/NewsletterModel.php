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

    /**
     * Inserta una nueva subscripcion al newsletter
     */
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

    /**
     * Verifica si un email ya esta suscrito
     */
    public function exists($email)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM newsletter_subscriptions WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM newsletter_subscriptions ORDER BY subscribed_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById(int $id): bool
    {
        $sql = "DELETE FROM newsletter_subscriptions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

}