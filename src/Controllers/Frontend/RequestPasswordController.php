<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\Round;
use App\Support\StringUtil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class RequestPasswordController
{
    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        $rounds = array_map(fn (Round $round) => [
            'url' => loginRoundUrl($round, 'pwforgot'),
            'name' => $round->name,
            'startdate' => $round->startdate > 0 ? StringUtil::dateFormat($round->startdate) : null,
        ], Round::active());

        return $view->render($response, 'frontend/rounds.html', [
            'site_title' => 'Passwort anfordern',
            'title' => 'Neues Passwort anfordern',
            'header_img' => 'pwrequest.png',
            'text' => 'Bitte wähle die Runde aus, in der sich dein Account befindet:',
            'rounds' => $rounds,
        ]);
    }
}
