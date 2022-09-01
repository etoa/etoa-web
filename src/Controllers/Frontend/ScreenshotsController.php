<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ScreenshotsController extends FrontendController
{
    /** @var string[] */
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

    private static string $baseUrl = '/images/screenshots';

    protected function getTitle(): string
    {
        return 'Bilder von EtoA';
    }

    protected function getSiteTitle(): ?string
    {
        return 'Screenshots';
    }

    protected function getHeaderImage(): string
    {
        return 'screenshots.png';
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $items = array_map(fn (String $f) => [
            'name' => ucfirst($f),
            'url' => self::$baseUrl . '/' . $f . '.jpg',
            'thumb_url' => self::$baseUrl . '/' . $f . '_small.jpg',
        ], self::$files);

        return parent::render($response, 'screenshots.html', [
            'items' => $items,
        ]);
    }
}
