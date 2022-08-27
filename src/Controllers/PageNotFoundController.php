<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\ForumBridge;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class PageNotFoundController
{
    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        $title = "Diese Seite wurde leider von einem Schwarzen Loch verschluckt! (Fehler 404)";
        $description = "Das tut uns leid! Zum Glück gibt es in unserem Universum noch genügend andere Seiten welche weit genug von Schwarzen Löchern entfernt sind.";

        return $view->render($response, 'error.html', [
            'code' => 404,
            'title' => $title,
            'description' => $description,
            'forumUrl' => ForumBridge::url(),
        ])->withStatus(404);
    }
}
