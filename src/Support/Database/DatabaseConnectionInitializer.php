<?php

declare(strict_types=1);

namespace App\Support\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class DatabaseConnectionInitializer
{
    public static function initialize(string $profile, string $wrapperClass): Connection
    {
        return DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'host' => config("database.$profile.host"),
            'port' => config("database.$profile.port", 3306),
            'dbname' => config("database.$profile.database"),
            'user' => config("database.$profile.user"),
            'password' => config("database.$profile.password"),
            'charset' => config("database.$profile.charset", 'utf8mb4'),
            'wrapperClass' => $wrapperClass,
            'driverOptions' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ]);
    }
}
