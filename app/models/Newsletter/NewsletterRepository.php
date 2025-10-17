<?php
namespace Models\Newsletter;
use Models\User\UserModel;

use PDO;

class NewsletterRepository
{
    protected $newsletterModel;
    protected $userModel;

    public function __construct(PDO $db)
    {
        $this->newsletterModel = new NewsletterModel($db);
        $this->userModel = new UserModel($db);
    }

    public function subscribe($email)
    {
        return $this->newsletterModel->subscribe($email);
    }

    public function isEmailSubscribed($email)
    {
        return $this->newsletterModel->exists($email);
    }

    // Devuelve un array de suscripciones al newsletter con paginación según el límite y el offset especificados
    public function getAllPaginated(int $limit, int $offset): array
    {
        return $this->newsletterModel->getPaginated($limit, $offset);
    }

    public function deleteById(string $email): bool
    {
        return $this->newsletterModel->deleteById($email);
    }

    public function getSubscribersCount(): int
    {
        return $this->newsletterModel->countSubscribers();
    }

    public function deleteByEmail(string $email): bool
    {
        return $this->newsletterModel->deleteByEmail($email);
    }

    public function isUserRegistered(string $email): bool
    {
        return (bool) $this->userModel->existsByEmail($email);
    }


}