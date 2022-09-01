<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

class BannerController extends TextPageController
{
    public function getTitle(): string
    {
        return 'Mache Werbung für EtoA';
    }

    public function getSiteTitle(): ?string
    {
        return 'Banner';
    }

    public function getTextKey(): string
    {
        return 'weitersagen';
    }

    public function getHeaderImage(): string
    {
        return 'banner.png';
    }
}
