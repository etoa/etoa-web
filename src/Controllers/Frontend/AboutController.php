<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\TextBlock;

class AboutController extends TextPageController
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
            new TextBlock(
                title: 'Die Entstehungsgeschichte von EtoA',
                content: $this->getTextContent('history')
            ),
        ];
    }
}
