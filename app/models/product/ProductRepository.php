<?php
namespace Models\Product;

use PDO;

class ProductRepository
{
    protected $productModel;

    public function __construct(PDO $db)
    {
        $this->productModel = new Product($db);
    }

    /*
    public function getCatalog()
    {
        return $this->productModel->getAll();
    }
    */

    /*
    public function getPaginatedProducts($limit, $offset)
    {
        return $this->productModel->getPaginated($limit, $offset);
    }
    */

    /*
    public function countAllProducts()
    {
        return $this->productModel->countAll();
    }
    */

    public function getFilteredPaginatedProducts($filter, $sort, $category, $limit, $offset, $search)
    {
        $sql = "SELECT * FROM products WHERE 1=1";
        $conditions = [];
        $params = [];

        if ($filter === 'in-stock') {
            $conditions[] = "stock > 0";
        }

        if ($filter === 'discounted') {
            $conditions[] = "is_on_sale = 1 AND price_discounted IS NOT NULL";
        }

        if (!empty($category)) {
            $conditions[] = "category_id = :category_id";
            $params[':category_id'] = $category;
        }

        if (!empty($search)) {
            $conditions[] = "name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        if ($conditions) {
            $sql .= ' AND ' . implode(' AND ', $conditions);
        }

        $allowedSorts = ['newest', 'top-sellers'];

        if (in_array($sort, $allowedSorts)) {
            switch($sort) {
                case 'newest': $sql .= " ORDER BY created_at DESC"; break;
                case 'top-sellers': $sql .= " ORDER BY sold_count DESC"; break;
            }
        } else {
            $sql .= " ORDER BY name ASC";
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = (int)$limit;
        $params[':offset'] = (int)$offset;

        return $this->productModel->executeQuery($sql, $params);

        // return $this    ->productModel->getFilteredPaginated($filter, $sort, $category, $limit, $offset, $search);
    }

    public function countFilteredProducts($filter, $category, $search)
    {
        $sql = "SELECT COUNT(*) FROM products WHERE 1=1";
        $conditions = [];
        $params = [];

        if ($filter === 'in-stock') {
            $conditions[] = "stock > 0";
        }

        if ($filter === 'discounted') {
            $conditions[] = "is_on_sale = 1 AND price_discounted IS NOT NULL";
        }

        if (!empty($category)) {
            $conditions[] = "category_id = :category_id";
            $params[':category_id'] = $category;
        }

        if (!empty($search)) {
            $conditions[] = "name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        if ($conditions) {
            $sql .= ' AND ' . implode(' AND ', $conditions);
        }

        return $this->productModel->executeCount($sql, $params);
    }
}