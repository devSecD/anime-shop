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

    public function attemptLogin(string $email, string $password): array
    {
        $user = $this->model->getByEmail($email);

        if (empty($user)) {
            return ['success' => false, 'message' => 'Correo electrónico no registrado.'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Contraseña incorrecta.'];
        }

        // puedes limpiar el array antes de devolverlo si no uieres exponer ciertos campos
        unset($user['password_hash']);

        return ['success' => true, 'message' => 'Inicio de sesión exitoso.', 'user' => $user];
    }

}