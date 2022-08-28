<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class TextPageController extends FrontendController
{
    protected abstract function getTextKey(): string;

    protected function getText(): ?string
    {
        return $this->getTextContent($this->getTextKey());
    }

    function __invoke(Request $request, Response $response): Response
    {
        return parent::render($response, 'text-page.html', [
            'text' => $this->getText(),
        ]);
    }
}
