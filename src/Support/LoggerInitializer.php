<?php

declare(strict_types=1);

namespace App\Support;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class LoggerInitializer
{
    public static function init(bool $debug): Logger
    {
        $logger = new Logger('app');
        $logger->pushHandler(new StreamHandler(STORAGE_DIR . '/logs/app.log', Level::Info));
        if ($debug) {
            $logger->pushHandler(new StreamHandler(STORAGE_DIR . '/logs/debug.log', Level::Debug));
        }

        return $logger;
    }
}
