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

     // Busca una dirección por id
    public function findById(int $id): ?array
    {
        return $this->model->findById($id);
    }

    // Valida que la direccion de envio pertenezca al usuario
    public function validateAddressBelongsToUser(int $addressId, int $userId): bool
    {
        $address = $this->model->findByIdAndUserId($addressId, $userId);
        return $address !== null;
    }

    // Obtiene todas las direcciones de envio que pertenecen al usuario
    public function getAllByUser(int $userId): array
    {
        return $this->model->getByUserId($userId);
    }

    // Guarda una nueva direccion de envio asociada al usuario
    public function saveAdress(array $data): array
    {
        $saved = $this->model->create($data);

        return $saved 
        ? ['success' => true, 'message' => 'Dirección guardada correctamente.'] 
        : ['success' => false, 'message' => 'No se pudo guarddar la dirección'];
    }
}