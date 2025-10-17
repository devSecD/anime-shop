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

    // Obtiene todas las preguntas frecuentes activas con su respectiva categoria
    public function getAllFAQs()
    {
        return $this->model->getActiveFAQs();
    }

    // Obtiene las preguntas frecuentes activas por la categoria que viene por parametro
    public function getFAQsByCategory($categoryName)
    {
        $faqs = $this->model->getActiveFAQs();
        // Filtra el array de FAQs activas y retorna solo las que pertenecen a la categoría especificada
        return array_filter($faqs, fn($f) => $f['category_name'] === $categoryName);
    }
}
