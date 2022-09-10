<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\TextBlock;

class LogoutController extends ContentPageController
{
    public function getSiteTitle(): string
    {
        return 'Logout';
    }

    public function getHeaderImage(): string
    {
        return 'logout.png';
    }

    protected function getBlocks(): array
    {
        return [
            new TextBlock(
                title: 'Logout',
                content: 'Du hast dich aus dem Spiel ausgeloggt.<br/> Wir wünschen weiterhin viel Spass im Web!',
            ),
        ];
    }
}
