<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\TextBlock;

class LogoutController extends TextPageController
{
    public function getSiteTitle(): string
    {
        return 'Logout';
    }

    public function getHeaderImage(): string
    {
        return 'logout.png';
    }

    protected function getTextBlocks(): array
    {
        return [
            new TextBlock(
                title: 'Logout',
                content: 'Du hast dich aus dem Spiel ausgeloggt.<br/> Wir wünschen weiterhin viel Spass im Web!',
            ),
        ];
    }
}
