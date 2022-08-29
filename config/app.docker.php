<?php

return [
    'database' => [
        'default' => [
            'host' => 'mysql',
            'database' => 'etoa-web',
            'user' => 'etoa-web',
            'password' => 'secret',
            'table_prefix' => '',
        ],
        'forum' => [
            'host' => 'mysql',
            'database' => 'etoa-web',
            'user' => 'etoa-web',
            'password' => 'secret',
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
];
