<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

class HistoryController extends TextPageController
{
    public function getTitle(): string
    {
        return 'Die Entstehungsgeschichte von EtoA';
    }

    public function getSiteTitle(): ?string
    {
        return 'Hintergrund';
    }

    public function getTextKey(): string
    {
        return "history";
    }

    public function getHeaderImage(): string
    {
        return 'history.png';
    }
}
