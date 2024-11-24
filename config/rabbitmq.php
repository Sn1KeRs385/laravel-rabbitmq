<?php

declare(strict_types=1);

return [
    'connections' => [
        'default' => [
            'host' => env('RABBIT_MQ_HOST', '127.0.0.1'),
            'port' => env('RABBIT_MQ_PORT', '5672'),
            'user' => env('RABBIT_MQ_USER', 'user'),
            'password' => env('RABBIT_MQ_PASSWORD', 'password'),
            'virtual_host' => env('RABBIT_MQ_VHOST', '/'),
        ],
    ],
];
