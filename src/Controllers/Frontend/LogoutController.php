<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

class LogoutController extends TextPageController
{
    public function getTitle(): string
    {
        return 'Logout';
    }

    public function getText(): string
    {
        return 'Du hast dich aus dem Spiel ausgeloggt.<br/>
        Wir wünschen weiterhin viel Spass im Web!';
    }

    public function getTextKey(): string
    {
        return '';
    }

    public function getHeaderImage(): string
    {
        return 'logout.png';
    }
}
