<?php
return [
    'catalog' => [
        'controller' => 'Home\IndexController', // Home\IndexController
        'action' => 'index'
    ],
    'newsletter/subscribe' => [
        'controller' => 'Newsletter\SubscribeController',
        'action' => 'index'
    ], 
    'user/register' => [
        'controller' => 'User\RegisterController', 
        'action' => 'index'
    ], 
    'user/login' => [
        'controller' => 'User\LoginController', 
        'action' => 'index'
    ]
];