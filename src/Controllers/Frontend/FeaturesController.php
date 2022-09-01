<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

class FeaturesController extends TextPageController
{
    public function getTitle(): string
    {
        return 'Über EtoA / Features';
    }

    public function getSiteTitle(): ?string
    {
        return 'Über EtoA';
    }

    public function getTextKey(): string
    {
        return 'features';
    }

    public function getHeaderImage(): string
    {
        return 'features.png';
    }
}
