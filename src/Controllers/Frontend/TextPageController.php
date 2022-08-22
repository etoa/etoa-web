<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Support\TextUtil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

abstract class TextPageController
{
    abstract function getTitle(): string;
    abstract function getTextKey(): string;
    abstract function getHeaderImage(): string;

    function getSiteTitle(): ?string
    {
        return null;
    }

    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        return $view->render($response, 'frontend/text-page.html', [
            'title' => $this->getTitle(),
            'site_title' => $this->getSiteTitle(),
            'header_img' => $this->getHeaderImage(),
            'text' => TextUtil::get($this->getTextKey()),
        ]);
    }
}
