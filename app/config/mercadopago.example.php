<?php

use App\Helpers\UrlHelper;

return [
    'public_key' => 'TU_TOKEN_AQUI',
    'access_token' => 'TU_PUBLIC_KEY_AQUI',
    'collector_id' => 123456,  // tu collector id
    'back_url'     => UrlHelper::base_url('payment/result')
];