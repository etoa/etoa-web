<?php

declare(strict_types=1);

namespace App\Support\Database;

class DB
{
    private static $instances = [];

    public static function instance(string $driver = 'default'): Connection
    {
        if (!isset(self::$instances[$driver])) {
            self::$instances[$driver] = new Connection($driver);
        }
        return self::$instances[$driver];
    }
}
