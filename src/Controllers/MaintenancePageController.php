<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Routing\AppRouteProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class MaintenancePageController
{
    public function __invoke(Request $request, Response $response, Twig $view): Response
    {
        return $view->render($response, 'maintenance.html', [
            'forumUrl' => config('forum.url', 'https://forum.etoa.ch/'),
            'message' => file_get_contents(AppRouteProvider::MAINTENANCE_PAGE_TRIGGER),
        ]);
    }
}
