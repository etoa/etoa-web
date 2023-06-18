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
        'timeout' => 300,
    ],
    'app' => [
        'locale' => 'de',
        'timezone' => 'Europe/Zurich',
        'environment' => App\Support\Environment::Development,
        'debug' => true,
    ],
    'auth' => [
        'admin' => [
            'usergroup' => 20,
        ],
    ],
    'forum' => [
        'url' => 'https://forum.etoa.ch/',
    ],
];
