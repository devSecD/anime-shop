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

    public function getCatalog()
    {
        return $this->productModel->getAll();
    }

    public function getPaginatedProducts($limit, $offset)
    {
        return $this->productModel->getPaginated($limit, $offset);
    }

    public function countAllProducts()
    {
        return $this->productModel->countAll();
    }
}