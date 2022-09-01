<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

class PrivacyController extends TextPageController
{
    public function getTitle(): string
    {
        return 'Datenschutzerklärung';
    }

    public function getTextKey(): string
    {
        return 'privacy';
    }

    public function getHeaderImage(): string
    {
        return 'privacy.png';
    }
}
