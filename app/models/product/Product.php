<?php
namespace Models\Product;

use Exception;
use PDO;
use PDOException;

class Product
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el producto: " . $e->getMessage());
        }
    }

    public function getPaginated($limit, $offset)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM products  LIMIT :limit offset :offset");
            $stmt->bindValue('limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue('offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener productos paginados: " . $e->getMessage());
        }
    }

    public function countAll()
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM products");
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Error al encontrar productos: " . $e->getMessage());
        }
    }
}