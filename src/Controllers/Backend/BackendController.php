<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class BackendController
{
    function __construct(protected Twig $view)
    {
    }

    protected abstract function getTitle(): string;

    protected function render(Response $response, string $backendTemplate, array $args): Response
    {
        return $this->view->render(
            $response,
            'backend/' . $backendTemplate,
            array_merge([
                'title' => $this->getTitle(),
            ], $args)
        );
    }
}
