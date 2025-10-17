<?php
namespace App\Models\Wishlist;

class WishlistRepository
{
    private $model;

    public function __construct(WishlistModel $model)
    {
        $this->model = $model;
    }

     // Agregar producto a la wishlist del usuario
    public function addItem(int $userId, int $productId): bool
    {
        // Primero validamos que no exista ya en la lista
        if ($this->model->exists($userId, $productId)) {
            return false; // Ya estaba en la wishlist
        }

        return $this->model->addItem($userId, $productId);
    }

     // Eliminar producto de la wishlist del usuario
    public function removeItem(int $userId, int $productId): bool
    {
        return $this->model->removeItem($userId, $productId);
    }

     // Verificar si un producto ya está en la wishlist del usuario
    public function exists($userId, $productId)
    {
        return $this->model->exists($userId, $productId);
    }

    public function getItems(int $userId): array
    {
        // retornamos todos los productos de la wishlist del usuario
        return $this->model->getByUser($userId);
    }

    // Se obtiene el total de productos de la wishlist
    public function getCount($userId) {
        $items = $this->getItems($userId);
        return count($items);
    }

}
