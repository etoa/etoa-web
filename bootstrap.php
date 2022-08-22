<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Load libraries
require __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Define environment and debug mode
$environment = ($_ENV['APP_ENV'] ?? 'production');
$debug = !in_array($environment, ['prod', 'production']);

// Init app
$app = AppFactory::create();

// Error handling
$app->addErrorMiddleware($debug, true, true);
ini_set('display_errors', '0');

// Base path
if (isset($_ENV['APP_BASEPATH'])) {
    $app->setBasePath($_ENV['APP_BASEPATH']);
}

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

// Run app
$app->run();
