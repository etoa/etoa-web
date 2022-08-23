<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\Round;
use App\Support\StringUtil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RequestPasswordController extends FrontendController
{
    protected function getTitle(): string
    {
        return 'Neues Passwort anfordern';
    }

    protected function getHeaderImage(): string
    {
        return 'pwrequest.png';
    }

    protected function getSiteTitle(): ?string
    {
        return 'Passwort anfordern';
    }

    function __invoke(Request $request, Response $response): Response
    {
        $rounds = array_map(fn (Round $round) => [
            'url' => loginRoundUrl($round, 'pwforgot'),
            'name' => $round->name,
            'startdate' => $round->startdate > 0 ? StringUtil::dateFormat($round->startdate) : null,
        ], Round::active());

        return parent::render($response, 'rounds.html', [
            'text' => 'Bitte wähle die Runde aus, in der sich dein Account befindet:',
            'rounds' => $rounds,
        ]);
    }
}
