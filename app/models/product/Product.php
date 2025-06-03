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

    /*
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
    */

    /*
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
    */

    /*
    public function countAll()
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM products");
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Error al encontrar productos: " . $e->getMessage());
        }
    }
    */

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