<?php

declare(strict_types=1);

return [
    'default' => 'rabbitmq', // Либо через .env поменять параметр на rabbitmq

    'connections' => [
        'rabbitmq' => [
            'driver' => 'rabbitmq', // Драйвер подключения
            'connection' => 'default', // Название подключения
            'channels_config' => [ // Конфиг для отправки
                'socket' => [ // Название, которое будет использоваться в ивентах в методе broadcastOn
                    'routing_key' => 'users.*', // Роутинг в rabbitmq
                    'only_event_name' => true, // Если true, то eventName будет отправляться только имя класса, если false то полностью путь вместе с неймспейсом
                ],
            ],
        ],
    ],
];
