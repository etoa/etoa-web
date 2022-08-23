<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\Round;
use App\Support\StringUtil;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class RegisterController
{
    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        $rounds = array_map(fn (Round $round) => [
            'url' => loginRoundUrl($round, 'register'),
            'name' => $round->name,
            'startdate' => $round->startdate > 0 ? StringUtil::dateFormat($round->startdate) : null,
        ], Round::active());

        return $view->render($response, 'frontend/rounds.html', [
            'site_title' => 'Registration',
            'title' => 'Melde dich für eine Runde an',
            'header_img' => 'register.png',
            'text' => 'Bitte wähle die Runde aus:',
            'rounds' => $rounds,
        ]);
    }
}
