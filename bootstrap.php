<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
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

// Init app
$app = AppFactory::create();

// Error handling
$app->addErrorMiddleware($debug, true, true);
if (!$debug) {
    ini_set('display_errors', '0');
}

// Base path
if (isset($_ENV['APP_BASEPATH'])) {
    $app->setBasePath($_ENV['APP_BASEPATH']);
}

// Add Twig-View Middleware
$twig = Twig::create(__DIR__ . '/templates', [
    'cache' => false,
]);
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/test', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'test.html', [
        'header_img' => 'news.png',
    ]);
})->setName('test');

// Run app
$app->run();
