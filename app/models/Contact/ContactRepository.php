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

    /**
     * Maneja la lógica de negocio antes de guardar
     */
    public function submitMessage(array $data): bool
    {
        // Puedes agregar validaciones extra aquí si lo deseas
        $data['name'] = trim($data['name']);
        $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $data['subject'] = trim($data['subject']);
        $data['message'] = trim($data['message']);

        return $this->model->saveMessage($data);
    }
}
