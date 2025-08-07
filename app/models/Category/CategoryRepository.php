<?php
namespace Models\Category;

class CategoryRepository
{
    private CategoryModel $model;

    public function __construct(CategoryModel $model) {
        $this->model = $model;
    }

    public function getAll(): array
    {
        return $this->model->getAll();
    }
}
