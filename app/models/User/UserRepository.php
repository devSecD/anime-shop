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

    public function getUserByEmail(string $email): ?array
    {
        return $this->model->getByEmail($email) ?: null;
    }

    public function register(array $data): array
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $created = $this->model->createUser($data);

        return $created 
            ? ['success' => true , 'message' => 'Usuario registrado exitosamente.']
            : ['success' => false , 'message' => 'Error al registrar el usuario.'];
    }

    public function attemptLogin(string $email, string $password): array
    {
        $user = $this->model->getByEmail($email);

        if (empty($user)) {
            return ['success' => false, 'message' => 'Correo electrónico no registrado.'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Contraseña incorrecta.'];
        }

        unset($user['password_hash']);

        return ['success' => true, 'message' => 'Inicio de sesión exitoso.', 'user' => $user];
    }

    public function findById(int $id): array
    {
        return $this->model->getById($id);
    }

    public function updatePassword(int $id, string $hash): bool
    {
        return $this->model->updatePassword($id, $hash);
    }

}