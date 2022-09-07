<?php

declare(strict_types=1);

namespace App\Support\Database;

use App\Support\Environment;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class DatabaseEntityManagerInitializer
{
    public static function initialize(Environment $environment): EntityManager
    {
        $cache = Environment::Production == $environment
            ? new FilesystemAdapter(directory: CACHE_DIR . '/doctrine', defaultLifetime: 300)
            : new ArrayAdapter();

        $config = ORMSetup::createAttributeMetadataConfiguration(
            [APP_DIR . '/src/Models'],
            isDevMode: Environment::Development == $environment,
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
