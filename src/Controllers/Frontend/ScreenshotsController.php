<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ScreenshotsController
{
    private static array $files = [
        'allianz',
        'auktion',
        'bauhof',
        'hilfe',
        'planet',
        'raumkarte',
        'userstatistik',
        'wirtschaft',
    ];

    private static string $baseUrl = "/images/screenshots";

    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        $items = [];
        foreach (self::$files as $f) {
            $items[] = [
                'name' => ucfirst($f),
                'url' =>  self::$baseUrl . "/" . $f . ".jpg",
                'thumb_url' => self::$baseUrl . "/" . $f . "_small.jpg",
            ];
        }

        return $view->render($response, 'frontend/screenshots.html', [
            'site_title' => 'Screenshots',
            'title' => 'Bilder von EtoA',
            'header_img' => 'screenshots.png',
            'items' => $items,
        ]);
    }
}
