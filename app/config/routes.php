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
    ],
    'user/logout' => [
        'controller' => 'User\LogoutController', 
        'action' => 'index'
    ],
    'user/forgot_password' => [
        'controller' => 'User\PasswordRecoveryController', 
        'action' => 'index'
    ],
    'user/reset_password' => [
        'controller' => 'User\ResetPasswordController', 
        'action' => 'index'
    ],
    'cart' => [
        'controller' => 'Cart\CartController', 
        'action' => 'view'
    ], 
    'cart/count' => [
        'controller' => 'Cart\CartController', 
        'action' => 'count'
    ], 
    'cart/add' => [
        'controller' => 'Cart\CartController', 
        'action' => 'add'
    ], 
    'cart/update' => [
        'controller' => 'Cart\CartController', 
        'action' => 'update'
    ], 
    'cart/remove' => [
        'controller' => 'Cart\CartController', 
        'action' => 'remove'
    ], 
    'checkout' => [
        'controller' => 'Checkout\CheckoutController', 
        'action' => 'index'
    ], 
    'checkout/payment' => [
        'controller' => 'Checkout\PaymentController', 
        'action' => 'index'
    ], 
    'webhook/mercadopago' => [
        'controller' => 'Webhook\WebhookController', 
        'action' => 'handle'
    ], 
    'payment/result/{status}/{payment_id}/{external_reference}' => [
        'controller' => 'Payment\ResultController', 
        'action' => 'index'
    ], 
];