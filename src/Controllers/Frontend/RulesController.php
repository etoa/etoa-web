<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\TextBlock;

class RulesController extends ContentPageController
{
    public function getSiteTitle(): ?string
    {
        return 'Regeln';
    }

    public function getHeaderImage(): string
    {
        return 'regeln.png';
    }

    protected function getBlocks(): array
    {
        $thread_id = $this->config->getInt('rules_thread', 0);
        $thread = $this->forum->thread($thread_id);

        return [
            new TextBlock(
                title: $thread?->topic ?? 'Es trat ein Fehler auf!',
                content: $thread?->message ?? 'Regeln nicht vorhanden!',
            ),
        ];
    }
}
