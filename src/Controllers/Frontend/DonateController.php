<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Support\TextUtil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class DonateController
{
    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        return $view->render($response, 'frontend/donate.html', [
            'site_title' => 'Spenden',
            'title' => 'Damit EtoA am Laufen bleibt...',
            'header_img' => 'spenden.png',
            'text' => TextUtil::get("spenden"),
        ]);
    }
}
