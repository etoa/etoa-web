<?php

declare(strict_types=1);

use App\Middleware\HttpsRedirectMiddleware;
use App\Support\TwigConfigurationInitializer;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Dotenv\Dotenv;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

// Load libraries
require __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Define environment and debug mode
$environment = ($_ENV['APP_ENV'] ?? 'production');
$debug = !in_array($environment, ['prod', 'production']);

$twig = TwigConfigurationInitializer::create($debug, !$debug);

// Init app
$container = new Container();
$container->set(Twig::class, fn () => $twig);
$app = Bridge::create($container);

// Error handling
$app->addErrorMiddleware($debug, true, true);
if (!$debug) {
    ini_set('display_errors', '0');
}

// HTTPS redirect
if (!$debug) {
    $app->add(HttpsRedirectMiddleware::class);
}

// Base path
$app->setBasePath($_ENV['APP_BASEPATH'] ?? getAppBasePath());

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

// Routing
require __DIR__ . '/src/routes/frontend.php';

// Run app
$app->run();
