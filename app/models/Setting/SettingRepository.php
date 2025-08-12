<?php
namespace Models\Setting;

use PDO;
class SettingRepository
{
    protected $model;

    public function __construct(PDO $db)
    {
        $this->model = new SettingModel($db);
    }

    public function getSettings()
    {
        return $this->model->getAll();
    }
    /**
     * Obtiene el valor de un setting por su clave
     *
     * @param string $key
     * @return string|null
     */
    public function getSettingValue(string $key): ?string
    {
        return $this->model->findValueByKey($key);
    }
    public function updateSetting($key, $value)
    {
        return $this->model->update($key, $value);
    }

    public function initializeIfEmpty(): void
    {
        $settings = $this->getSettings();
        if (empty($settings)) {
            $defaults = [
                ['key' => 'site_name', 'value' => 'Anime Shop'],
                ['key' => 'logo_path', 'value' => 'default_logo.png'],
                ['key' => 'contact_email', 'value' => 'contacto@anime-shop.com'],
                ['key' => 'items_per_page', 'value' => 8],
                ['key' => 'timezone', 'value' => 'America/Mexico_City'],
                ['key' => 'currency', 'value' => 'MXN'],
            ];

            foreach ($defaults as $setting) {
                $this->model->insert($setting['key'], $setting['value']);
            }
        }
    }
}
