<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\Round;
use App\Repository\RoundRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterController extends FrontendController
{
    protected function getTitle(): string
    {
        return 'Melde dich für eine Runde an';
    }

    protected function getHeaderImage(): string
    {
        return 'register.png';
    }

    protected function getSiteTitle(): ?string
    {
        return 'Registration';
    }

    public function __invoke(Request $request, Response $response, RoundRepository $rounds): Response
    {
        $rounds = array_map(fn (Round $round) => [
            'url' => $rounds->createPageUrl($round, 'register'),
            'name' => $round->name,
            'startDate' => $round->startDate,
        ], $rounds->active());

        return parent::render($response, 'rounds.html.twig', [
            'text' => 'Bitte wähle die Runde aus:',
            'rounds' => $rounds,
        ]);
    }
}
