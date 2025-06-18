<?php
$env = require __DIR__ . '/env.php';

if ($env['env'] === 'production') {
    return require __DIR__ . '/email_production.php';
} else {
    return require __DIR__ . '/email_local.php';
}
