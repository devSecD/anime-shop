<?php
namespace Models\FAQ;

use PDO;

class FAQRepository
{
    private $model;

    public function __construct(PDO $db)
    {
        $this->model = new FAQModel($db);
    }

    public function getAllFAQs()
    {
        return $this->model->getActiveFAQs();
    }

    public function getFAQsByCategory($categoryName)
    {
        $faqs = $this->model->getActiveFAQs();
        return array_filter($faqs, fn($f) => $f['category_name'] === $categoryName);
    }
}
