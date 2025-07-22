<?php

namespace Models\Checkout;

use PDO;

class ShippingAddressRepository
{
    private ShippingAddressModel $model;

    public function __construct(PDO $db)
    {
        $this->model = new ShippingAddressModel($db);
    }

    /**
     * Busca una dirección por id
     */
    public function findById(int $id): ?array
    {
        return $this->model->findById($id);
    }

// Repository
    public function validateAddressBelongsToUser(int $addressId, int $userId): bool
    {
        $address = $this->model->findByIdAndUserId($addressId, $userId);
        return $address !== null;
    }


    public function getAllByUser(int $userId): array
    {
        return $this->model->getByUserId($userId);
    }

    public function saveAdress(array $data): array
    {
        $saved = $this->model->create($data);

        return $saved 
        ? ['success' => true, 'message' => 'Dirección guardada correctamente.'] 
        : ['success' => false, 'message' => 'No se pudo guarddar la dirección'];
    }
}