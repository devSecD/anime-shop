<?php
namespace Models\FAQ;

use PDO;

class FAQModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Obtener todas las FAQs activas con categoría
    public function getActiveFAQs()
    {
        $stmt = $this->db->prepare("
            SELECT f.*, c.name AS category_name
            FROM faqs f
            LEFT JOIN faq_categories c ON f.category_id = c.faq_categorie_id
            WHERE f.status = 1 AND c.status = 1
            ORDER BY c.sort_order ASC, f.sort_order ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFAQById($id)
    {
        $stmt = $this->db->prepare("
            SELECT f.*, c.name AS category_name
            FROM faqs f
            LEFT JOIN faq_categories c ON f.category_id = c.faq_categorie_id
            WHERE f.faq_id = :id AND f.status = 1
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
