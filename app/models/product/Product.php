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

    /**
     * Devuelve un producto por ID
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM products WHERE product_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: null;
    }

    public function getByIds(array $ids, string $in): array
    {
        $sql = "SELECT * FROM products WHERE product_id IN($in)";
        $stmt = $this->db->prepare($sql);

        foreach($ids as $idx => $id) {
            $stmt->bindValue($idx + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeQuery($sql, $params)
    {
        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener productos filtrados: " . $e->getMessage());
        }
    }

    public function executeCount($sql, $params)
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
    public function interpolateNamedQuery($query, $params)
    {
        foreach ($params as $key => $value) {
            $quoted = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $query = str_replace($key, $quoted, $query);
        }
        return $query;
    }
}