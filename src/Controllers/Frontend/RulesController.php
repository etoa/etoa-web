<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Support\ForumBridge;

class RulesController extends TextPageController
{
    private array $thread;

    public function __construct()
    {
        $thread_id = get_config('rules_thread', 0);
        $this->thread = ForumBridge::thread($thread_id) ?? [];
    }

    public function getTitle(): string
    {
        return $this->thread['subject'] ?? 'Es trat ein Fehler auf!';
    }

    public function getSiteTitle(): ?string
    {
        return 'Regeln';
    }

    public function getText(): string
    {
        return $this->thread["message"] ?? 'Regeln nicht vorhanden!';
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
