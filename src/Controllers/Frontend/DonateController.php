<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DonateController extends FrontendController
{
    protected function getTitle(): string
    {
        return 'Damit EtoA am Laufen bleibt...';
    }

    protected function getHeaderImage(): string
    {
        return 'spenden.png';
    }

    protected function getSiteTitle(): ?string
    {
        return 'Spenden';
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return parent::render($response, 'donate.html.twig', [
            'text' => $this->getTextContent('spenden'),
        ]);
    }
}
