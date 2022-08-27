<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class MaintenancePageController
{
    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        return $view->render($response, 'maintenance.html');
    }
}