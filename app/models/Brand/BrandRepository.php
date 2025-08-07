<?php
namespace Models\Brand;

class BrandRepository
{
    private BrandModel $model;

    public function __construct(BrandModel $model) {
        $this->model = $model;
    }

    public function getAll(): array
    {
        return $this->model->getAll();
    }
}
