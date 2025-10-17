<?php

namespace Models\Checkout;

use PDO;
use PDOException;

class ShippingAddressModel
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

     // Busca dirección por id
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM shipping_addresses WHERE address_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $address = $stmt->fetch(PDO::FETCH_ASSOC);
        return $address ?: null;
    }

    public function findByIdAndUserId(int $addressId, int $userId): ?array
    {
        $sql = "SELECT * FROM shipping_addresses WHERE address_id = :addressId AND user_id = :userId LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':addressId', $addressId, PDO::PARAM_INT);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    public function getByUserId(int $userId): array
    {
        $sql = "SELECT * FROM shipping_addresses WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        try {
            $sql = "INSERT INTO shipping_addresses 
                    (user_id, fullname, email, phone, street, neighborhood, postal_code, city, state, country, notes) 
                    VALUES 
                    (:user_id, :fullname, :email, :phone, :street, :neighborhood, :postal_code, :city, :state, :country, :notes)";
            
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':fullname', $data['fullname']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':phone', $data['phone']);
            $stmt->bindValue(':street', $data['street']);
            $stmt->bindValue(':neighborhood', $data['neighborhood']);
            $stmt->bindValue(':postal_code', $data['postal_code']);
            $stmt->bindValue(':city', $data['city']);
            $stmt->bindValue(':state', $data['state']);
            $stmt->bindValue(':country', $data['country']);
            $stmt->bindValue(':notes', $data['notes']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }
}