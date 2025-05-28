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

    public function getFilteredPaginated($filter, $sort, $category, $limit, $offset)
    {
        try {
            $sql = "SELECT * FROM products WHERE 1=1";

            if ($filter === 'in-stock') {
                $sql .= " AND stock > 0";
            }

            if ($filter === 'discounted') {
                $sql .= " AND is_on_sale AND price_discounted IS NOT NULL";
            }

            if (!empty($category)) {
                $sql .= " AND category_id = :category_id";
            }

            // Ordenamiento
            if ($sort === 'newest') {
                $sql .= " ORDER BY created_at DESC";
            } elseif ($sort === 'top-sellers') {
                $sql .= " ORDER BY sold_count DESC";
            } else {
                $sql .= " ORDER BY name ASC";
            }

            $sql .= " LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);

            if (!empty($category)) {
                $stmt->bindValue(':category_id', (int)$category, PDO::PARAM_INT);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            // echo "<pre>";
            // print_r($this->interpolateNamedQuery($sql, $params)); // para params puede ser = [$var1, $var2...]
            // echo "</pre>";
            // exit("XD");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener productos filtrados: " . $e->getMessage());
        }
    }

    public function countFiltered($filter, $category)
    {
        try {
            $sql = "SELECT COUNT(*) FROM products WHERE 1=1";
            $params = [];

            if ($filter === 'in-stock') {
                $sql .= " AND stock > 0";
            }

            if ($filter === 'discounted') {
                $sql .= " AND price_discounted IS NOT NULL";
            }

            if (!empty($category)) {
                $sql .= " AND category_id = ?";
                $params[] = $category;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Error al contar productos filtrados: " . $e->getMessage());
        }
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