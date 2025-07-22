<?php

return [
    'dsn' => 'mysql:host=localhost;dbname=nombre_base_de_datos;charset=utf8mb4',
    'username' => 'usuario_de_la_base_dde_datos', 
    'password' => 'password_de_la_base_de_datos', 
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ],
];