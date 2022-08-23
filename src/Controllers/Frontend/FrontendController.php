<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class FrontendController
{
    function __construct(protected Twig $view)
    {
    }

    protected abstract function getTitle(): string;
    protected abstract function getHeaderImage(): string;

    protected function getSiteTitle(): ?string
    {
        return null;
    }

    protected function render(Response $response, string $frontendTemplate, array $args): Response
    {
        return $this->view->render(
            $response,
            'frontend/' . $frontendTemplate,
            array_merge([
                'title' => $this->getTitle(),
                'site_title' => $this->getSiteTitle(),
                'header_img' => $this->getHeaderImage(),
            ], $args)
        );
    }
}
