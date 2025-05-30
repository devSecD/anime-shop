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
}