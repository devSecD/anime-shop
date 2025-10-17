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
        // se niega el empty para validar que el email del usuario existe, si existe es true y si no es false
        return !empty($this->model->getByEmail($email));
    }

    public function getUserByEmail(string $email): ?array
    {
        return $this->model->getByEmail($email) ?: null;
    }

    public function register(array $data): array
    {
        // PASSWORD_DEFAULT => alias que apunta al algoritmo recomendado por PHP para nuevas aplicaciones.
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $created = $this->model->createUser($data);

        return $created 
            ? ['success' => true , 'message' => 'Usuario registrado exitosamente.']
            : ['success' => false , 'message' => 'Error al registrar el usuario.'];
    }

    // Metodo del loguin del usuario
    public function attemptLogin(string $email, string $password): array
    {
        $user = $this->model->getByEmail($email);

        if (empty($user)) {
            return ['success' => false, 'message' => 'Correo electrónico no registrado.'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Contraseña incorrecta.'];
        }

        unset($user['password_hash']); // por seguridad se quita el password aunque este con hash aplicado

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

    public function registerWithRole(array $data, string $roleName): array
    {
        try {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $userId = $this->model->createUser($data);

            if (!$userId) {
                return ['success' => false, 'message' => 'Error al registrar el usuario.'];
            }

            // Obtener ID del rol por nombre
            $roleId = $this->model->getRoleIdByName($roleName);
            if (!$roleId) {
                return ['success' => false, 'message' => "El rol '$roleName' no existe."];
            }

            // Asignar rol al usuario
            $assigned = $this->model->assignRoleToUser($userId, $roleId);
            if (!$assigned) {
                return ['success' => false, 'message' => 'Error al asignar rol al usuario.'];
            }

            return ['success' => true, 'message' => 'Usuario registrado exitosamente con rol asignado.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error inesperado: ' . $e->getMessage()];
        }
    }

    public function countUsers(?string $role = null): int
    {
        // Si se pasa un rol, usar getUsersCountByRole del modelo, si no, countAllUsers
        return $role
            ? $this->model->getUsersCountByRole($role)
            : $this->model->countAllUsers();
    }

    public function getUserRoles(int $userId): array 
    {
        return $this->model->getUserRoles($userId);
    }

    public function getUsers(?string $role = null, int $limit = 20, int $offset = 0): array
    {
        return $role
            ? $this->model->getUsersByRole($role, $limit, $offset)
            : $this->model->getPaginatedUsers($limit, $offset);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->model->findByEmail($email);
    }

    public function update(int $userId, array $data): array
    {
        try {
            $success = $this->model->update($userId, $data);
            return [
                'success' => $success,
                'message' => $success ? '' : 'No se pudo actualizar el perfil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }   

}