<?php
namespace Models\Setting;

use PDO;

class SettingModel
{
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM settings");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Busca el valor de un setting por su clave
     *
     * @param string $key
     * @return string|null
     */
    public function findValueByKey(string $key): ?string
    {
        $sql = "SELECT value FROM settings WHERE `key` = :key LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':key', $key, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['value'] ?? null;
    }
    public function update($key, $value)
    {
        $sql = "UPDATE settings SET value = :value WHERE `key` = :key";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':key' => $key, ':value' => $value]);
    }

    public function insert(string $key, string $value): bool
    {
        $sql = "INSERT INTO settings (`key`, `value`) VALUES (:key, :value)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':key' => $key, ':value' => $value]);
    }
}
