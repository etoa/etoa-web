<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\GalleryBlock;
use App\UI\TextBlock;

class AboutController extends ContentPageController
{
    public function getSiteTitle(): ?string
    {
        return 'Über EtoA';
    }

    public function getHeaderImage(): string
    {
        return 'features.png';
    }

    protected function getTextBlocks(): array
    {
        return [
            new TextBlock(
                title: 'Vor langer Zeit in einer Galaxie weit weit entfernt...',
                content: $this->getTextContent('story')
            ),
            new TextBlock(
                title: 'Über EtoA / Features',
                content: $this->getTextContent('features')
            ),
            new GalleryBlock(
                title: 'Bilder von EtoA',
                images: $this->getScreenshots(),
            ),
            new TextBlock(
                title: 'Die Entstehungsgeschichte von EtoA',
                content: $this->getTextContent('history')
            ),
        ];
    }

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

    /**
     * @return array<integer,array<string,string>>
     */
    private function getScreenshots(): array
    {
        return array_map(fn (String $f) => [
            'name' => ucfirst($f),
            'url' => self::$baseUrl . '/' . $f . '.jpg',
            'thumb_url' => self::$baseUrl . '/' . $f . '_small.jpg',
        ], self::$files);
    }
}
