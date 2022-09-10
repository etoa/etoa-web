<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\CustomTemplateBlock;
use App\UI\TextBlock;

class SupportUsController extends ContentPageController
{
    public function getSiteTitle(): ?string
    {
        return 'Unterstütze uns';
    }

    public function getHeaderImage(): string
    {
        return 'spenden.png';
    }

    protected function getBlocks(): array
    {
        return [
            new TextBlock(
                title: 'Damit EtoA am Laufen bleibt...',
                content: $this->getTextContent('spenden')
            ),
            new CustomTemplateBlock(
                template: 'components/donate-block.html',
                data: ['title' => 'Onlinespende'],
            ),
            new TextBlock(
                title: 'Mache Werbung für EtoA',
                content: $this->getTextContent('weitersagen'),
            ),
        ];
    }
}
