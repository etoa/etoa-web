<?php


declare(strict_types=1);

use App\Middleware\HttpsRedirectMiddleware;
use App\Routing\AppRouteProvider;
use App\Support\Database\DatabaseEntityManagerInitializer;
use App\Support\TwigConfigurationInitializer;
use Carbon\Carbon;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;
use Slim\Middleware\Session;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

define('APP_DIR', __DIR__ . '/../');

// Load libraries
require APP_DIR . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(APP_DIR);
$dotenv->safeLoad();

// Define environment and debug mode
$environment = ($_ENV['APP_ENV'] ?? 'production');
$debug = !in_array($environment, ['prod', 'production']);

// Define locale
Carbon::setLocale($_ENV['LOCALE'] ?? 'de');

$twig = TwigConfigurationInitializer::create($debug, !$debug);

// Init app
$container = new Container();
$container->set(Twig::class, fn () => $twig);
$container->set(EntityManager::class, DatabaseEntityManagerInitializer::initialize($debug));

$app = Bridge::create($container);

// Error handling
$app->addErrorMiddleware(
    displayErrorDetails: $debug,
    logErrors: true,
    logErrorDetails: true
);
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

// Add session management
$app->add(Session::class);

// Routing
$app->group('', new AppRouteProvider($debug));

// Run app
$app->run();
