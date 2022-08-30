<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

class DisclaimerController extends TextPageController
{
    public function getTitle(): string
    {
        return 'Haftungsausschluss';
    }

    public function getTextKey(): string
    {
        return "disclaimer";
    }

    public function getHeaderImage(): string
    {
        return 'disclaimer.png';
    }
}
