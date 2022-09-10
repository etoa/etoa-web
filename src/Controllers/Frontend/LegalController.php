<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\TextBlock;

class LegalController extends TextPageController
{
    public function getSiteTitle(): string
    {
        return 'Rechtliches (Impressum, Haftungsausschluss & Datenschutzerklärung)';
    }

    public function getHeaderImage(): string
    {
        return 'impressum.png';
    }

    protected function getTextBlocks(): array
    {
        return [
            new TextBlock(
                title: 'Impressum',
                content: $this->getTextContent('impressum')
            ),
            new TextBlock(
                title: 'Haftungsausschluss',
                content: $this->getTextContent('disclaimer'),
            ),
            new TextBlock(
                title: 'Datenschutzerklärung',
                content: $this->getTextContent('privacy'),
            ),
        ];
    }
}
