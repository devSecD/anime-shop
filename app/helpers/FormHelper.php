<?php
namespace App\Helpers;

class FormHelper
{
    /**
     * Retorna la URL de acción del formulario según el contexto.
     *
     * @param string $page Nombre de la página o tipo de formulario (ej. 'registerProduct', 'updateProduct')
     * @return string URL de acción del formulario
     */
    public static function getProductFormAction(string $page): string
    {
        switch ($page) {
            case 'registerProduct':
                return '/anime-shop/public/admin/product/create/process';
            case 'updateProduct':
                return '/anime-shop/public/admin/product/update/process'; // aún no creada
            default:
                return '';
        }
    }

   public static function getProductFormTitle(string $page): string
    {
        switch ($page) {
            case 'registerProduct':
                return 'Registrar nuevo producto';
            case 'updateProduct':
                return 'Actualizar producto'; // aún no creada
            default:
                return '';
        }
    }

    public static function getProductFormTextButton(string $page): string 
    {
        switch($page) {
            case 'registerProduct':
                return 'Guardar';
            case 'updateProduct';
                return 'Actualizar';
            default:
            return '';
        }
    }

    public static function getProductFormId(string $page): string 
    {
        switch($page) {
            case 'registerProduct':
                return 'createForm';
            case 'updateProduct':
                return 'updateForm';
            default:
            return '';
        }
    }

    public static function getProductFormButtonId(string $page): string 
    {
        switch($page) {
            case 'registerProduct':
                return 'btn-add-product';
            case 'updateProduct':
                return 'btn-update-product';
            default:
            return '';
        }
    }

    /**
     * Retorna el valor para el campo de formulario
     * 
     * @param string $field Nombre del campo (ej: 'name', 'price', 'description')
     * @param array $data Datos actuales (producto) o []
     * @param mixed $default Valor por defecto si no existe en $data
     * @return string Valor a colocar en el atributo value o en textarea
     */
    public static function getFieldValue(string $field, array $data = [], $default = ''): string
    {
        if (isset($data[$field])) {
            // Escapar para HTML, evitar XSS
            return htmlspecialchars($data[$field], ENT_QUOTES, 'UTF-8');
        }
        return htmlspecialchars($default, ENT_QUOTES, 'UTF-8');
    }

    public static function isCheckboxChecked(string $field, array $data = []): string
    {
        return isset($data[$field]) && $data[$field] == 1 ? 'checked' : '';
    }

    public static function isSelected(string $field, $optionValue, array $data = []): string
    {
        return isset($data[$field]) && $data[$field] == $optionValue ? 'selected' : '';
    }

}
