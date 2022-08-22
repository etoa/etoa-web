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
];
