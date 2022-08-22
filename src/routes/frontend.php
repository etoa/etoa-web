<?php

declare(strict_types=1);

use App\Controllers\StoryController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** @var \Slim\App $app */

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/test', StoryController::class)->setName('test');
