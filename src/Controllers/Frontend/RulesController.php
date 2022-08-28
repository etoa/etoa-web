<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Support\ForumBridge;

class RulesController extends TextPageController
{
    private function getThread(): array
    {
        $thread_id = get_config('rules_thread', 0);
        return ForumBridge::thread($thread_id) ?? [];
    }

    public function getTitle(): string
    {
        return $this->getThread()['subject'] ?? 'Es trat ein Fehler auf!';
    }

    public function getSiteTitle(): ?string
    {
        return 'Regeln';
    }

    public function getText(): string
    {
        return $this->getThread()["message"] ?? 'Regeln nicht vorhanden!';
    }

    public function getTextKey(): string
    {
        return '';
    }

    public function getHeaderImage(): string
    {
        return 'regeln.png';
    }
}
