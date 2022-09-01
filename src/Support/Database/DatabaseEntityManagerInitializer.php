<?php

declare(strict_types=1);

namespace App\Support\Database;

use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class DatabaseEntityManagerInitializer
{
    public static function initialize(bool $debug): EntityManager
    {
        $cache = $debug ?
            DoctrineProvider::wrap(new ArrayAdapter()) :
            DoctrineProvider::wrap(new FilesystemAdapter(directory: APP_DIR . '/storage/cache/doctrine'));

        $config = Setup::createAttributeMetadataConfiguration(
            [APP_DIR . '/src/Models'],
            isDevMode: $debug,
            cache: $cache
        );

        return EntityManager::create([
            'driver' => 'pdo_mysql',
            'host' => config('database.default.host'),
            'port' => 3306,
            'dbname' => config('database.default.database'),
            'user' => config('database.default.user'),
            'password' => config('database.default.password'),
            'charset' => config('database.default.charset', 'utf8'),
        ], $config);
    }
}
