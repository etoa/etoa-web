<?php

namespace App\Controllers\FrontendNg;

use App\Controllers\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeController extends AbstractController
{
    public function __invoke(Request $request, Response $response, Twig $view): Response
    {
        return $view->render($response, 'frontend_ng/home.html', []);
    }
}
