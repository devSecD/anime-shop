<?php
namespace Models\User;

use PDO;

class UserRepository
{
    private $model;

    public function __construct(PDO $db)
    {
        $this->model = new UserModel($db);
    }

    public function emailExists(string $email): bool
    {
        return !empty($this->model->getByEmail($email));
    }

    public function register(array $data): array
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $created = $this->model->createUser($data);

        return $created 
            ? ['success' => true , 'message' => 'Usuario registrado exitosamente.']
            : ['success' => false , 'message' => 'Error al registrar el usuario.'];
    }
}