<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\TextUtil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class StoryController {

    function __invoke(Request $request, Response $response): Response
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'story.html', [
            'text' => TextUtil::get("story"),
        ]);
    }
}
