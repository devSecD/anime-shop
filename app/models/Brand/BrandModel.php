<?php
namespace Models\Brand;

use PDO;

class BrandModel
{
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    public function getAll(): array
    {
        $sql = "SELECT brand_id, name FROM brands ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
