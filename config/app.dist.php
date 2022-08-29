<?php

return [
    'database' => [
        'default' => [
            'host' => 'localhost',
            'database' => 'your_database',
            'user' => 'your_user',
            'password' => 'your_password',
            'table_prefix' => '',
        ],
        'forum' => [
            'host' => 'localhost',
            'database' => 'your_database',
            'user' => 'your_user',
            'password' => 'your_password',
        ],
    ],
    'caching' => [
        'apcu_timeout' => 300,
    ],
    'app' => [
        'locale' => 'de',
        'timezone' => 'Europe/Zurich',
        'debug' => false,
    ],
    'auth' => [
        'admin' => [
            'usergroup' => 20,
        ]
    ],
    'forum' => [
        'url' => 'https://forum.etoa.ch/',
    ],
];
