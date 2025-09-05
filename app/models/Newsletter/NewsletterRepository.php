<?php
namespace Models\Newsletter;

use PDO;

class NewsletterRepository
{
    protected $newsletterModel;

    public function __construct(PDO $db)
    {
        $this->newsletterModel = new NewsletterModel($db);
    }

    public function subscribe($email)
    {
        return $this->newsletterModel->subscribe($email);
    }

    public function isEmailSubscribed($email)
    {
        return $this->newsletterModel->exists($email);
    }

    public function getAll(): array
    {
        return $this->newsletterModel->getAll();
    }

    public function deleteById(int $id): bool
    {
        return $this->newsletterModel->deleteById($id);
    }

    public function getSubscribersCount(): int
    {
        return $this->newsletterModel->countSubscribers();
    }

    public function deleteByEmail(string $email): bool
    {
        return $this->newsletterModel->deleteByEmail($email);
    }

}