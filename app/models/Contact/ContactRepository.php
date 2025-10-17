<?php
namespace Models\Contact;

use PDO;

class ContactRepository
{
    private $model;

    public function __construct(PDO $db)
    {
        $this->model = new Contact($db);
    }

     // Valida y limpia los datos del formulario de contacto antes de guardarlos en la base de datos
    public function submitMessage(array $data): bool
    {
        $data['name'] = trim($data['name']);
        $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $data['subject'] = trim($data['subject']);
        $data['message'] = trim($data['message']);

        return $this->model->saveMessage($data);
    }
}
