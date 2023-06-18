<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\ForumBridge;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class UnauthorizedRequestController
{
    public function __construct(private readonly Twig $view)
    {
    }

    public function __invoke(Response $response): Response
    {
        return $this->view->render($response, 'error.html.twig', [
            'code' => StatusCodeInterface::STATUS_UNAUTHORIZED,
            'title' => 'Nicht authentisiert!',
            'description' => 'Diese Seite ist nur für authentisierte Benutzer verfügbar.',
            'forumUrl' => ForumBridge::url(),
        ])->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED);
    }
}
