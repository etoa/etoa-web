<?php

declare(strict_types=1);

use App\Middleware\HttpsRedirectMiddleware;
use App\Routing\AppRouteProvider;
use App\Support\Database\DatabaseConnectionInitializer;
use App\Support\Database\DatabaseEntityManagerInitializer;
use App\Support\Database\ForumDatabaseConnection;
use App\Support\Environment;
use App\Support\TwigConfigurationInitializer;
use Carbon\Carbon;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Slim\Middleware\Session;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

define('APP_DIR', __DIR__ . '/../');
define('STORAGE_DIR', APP_DIR . '/storage');
define('CACHE_DIR', STORAGE_DIR . '/cache');

// Load libraries
require APP_DIR . '/vendor/autoload.php';

/** @var bool $debug */
$debug = config('app.debug', false);

/** @var Environment $environment */
$environment = config('app.environment', Environment::Production);

// Clear cache if requested
if (file_exists(CACHE_DIR) && file_exists(STORAGE_DIR . '/clear_cache.trigger')) {
    destroy_dir(CACHE_DIR);
    unlink(STORAGE_DIR . '/clear_cache.trigger');
}

// Define locale
Carbon::setLocale(config('app.locale', 'de'));

$twig = TwigConfigurationInitializer::create(
    debug: $debug,
    caching: Environment::Production == $environment,
);

// Init app
$container = new Container();
$container->set(Twig::class, fn () => $twig);
$container->set(EntityManager::class, DatabaseEntityManagerInitializer::initialize($environment));
$container->set(ForumDatabaseConnection::class, DatabaseConnectionInitializer::initialize('forum', ForumDatabaseConnection::class));

$app = Bridge::create($container);

$logger = new Logger('app');
$logger->pushHandler(new StreamHandler(STORAGE_DIR . '/logs/app.log', Level::Info));
if ($debug) {
    $logger->pushHandler(new StreamHandler(STORAGE_DIR . '/logs/debug.log', Level::Debug));
}
$container->set(Logger::class, fn () => $logger);

// Error handling
$app->addErrorMiddleware(
    displayErrorDetails: $debug,
    logErrors: true,
    logErrorDetails: true,
    logger: $logger,
);
if (!$debug) {
    ini_set('display_errors', '0');
}

// HTTPS redirect
if (Environment::Production == $environment) {
    $app->add(HttpsRedirectMiddleware::class);
}

// Base path
$app->setBasePath(getAppBasePath());

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

// Add session management
$app->add(Session::class);

// Routing
$app->group('', new AppRouteProvider(
    $container,
    caching: Environment::Production == $environment
));

// Run app
$app->run();
