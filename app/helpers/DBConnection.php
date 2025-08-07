<?php
namespace App\Helpers;

class DBConnection
{
    public static function get(): \PDO
    {
        $config = require APP_PATH . '/config/database.php';

        return new \PDO(
            $config['dsn'], 
            $config['username'], 
            $config['password'], 
            $config['options']
        );
    }
}
