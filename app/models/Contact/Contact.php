<?php
namespace Models\Contact;

use PDO;
use PDOException;

class Contact
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Guarda un mensaje de contacto en la base de datos
     */
    public function saveMessage(array $data): bool
    {
        $sql = "INSERT INTO contacts (name, email, subject, message, created_at)
                VALUES (:name, :email, :subject, :message, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':subject', $data['subject']);
        $stmt->bindValue(':message', $data['message']);
        return $stmt->execute();
    }
}
