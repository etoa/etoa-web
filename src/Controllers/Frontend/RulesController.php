<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\Forum\Thread;

class RulesController extends TextPageController
{
    private function getThread(): ?Thread
    {
        $thread_id = $this->config->getInt('rules_thread');

        return $this->forum->thread($thread_id);
    }

    public function getTitle(): string
    {
        return $this->getThread()?->topic ?? 'Es trat ein Fehler auf!';
    }

    public function getSiteTitle(): ?string
    {
        return 'Regeln';
    }

    public function getText(): string
    {
        return $this->getThread()?->message ?? 'Regeln nicht vorhanden!';
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
