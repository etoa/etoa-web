<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

class StoryController extends TextPageController
{
    public function getTitle(): string
    {
        return 'Vor langer Zeit in einer Galaxie weit weit entfernt...';
    }

    public function getTextKey(): string
    {
        return 'story';
    }

    public function getSiteTitle(): ?string
    {
        return 'Story';
    }

    public function getHeaderImage(): string
    {
        return 'story.png';
    }
}
