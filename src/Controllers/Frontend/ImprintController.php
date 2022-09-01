<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

class ImprintController extends TextPageController
{
    public function getTitle(): string
    {
        return 'Wer für dieses Projekt verantwortlich ist';
    }

    public function getSiteTitle(): string
    {
        return 'Haftungsausschluss';
    }

    public function getTextKey(): string
    {
        return 'impressum';
    }

    public function getHeaderImage(): string
    {
        return 'impressum.png';
    }
}
