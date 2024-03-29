<?php

use Adbar\Dot;

/**
 * Fetches a config value from the site config file.
 *
 * @param string $key key in dot notation
 */
function config(string $key, mixed $default = null): mixed
{
    $file = APP_DIR . '/config/app.php';
    if (!is_file($file)) {
        exit('Application is not configured properly, please set-up the configuration file (config/app.php).');
    }
    $config = require $file;
    $dot = new Dot($config);

    return $dot->get($key, $default);
}

function getAppBasePath(): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $uri = (string) parse_url('http://a' . ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    if (0 === stripos($uri, $_SERVER['SCRIPT_NAME'])) {
        return $_SERVER['SCRIPT_NAME'];
    }
    if ('/' !== $scriptDir && 0 === stripos($uri, $scriptDir)) {
        return $scriptDir;
    }

    return '';
}

function destroy_dir(string $dir): bool
{
    if (!is_dir($dir) || is_link($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $file) {
        if ('.' == $file || '..' == $file) {
            continue;
        }
        if (!destroy_dir($dir . '/' . $file)) {
            chmod($dir . '/' . $file, 0777);
            if (!destroy_dir($dir . '/' . $file)) {
                return false;
            }
        }
    }

    return rmdir($dir);
}
