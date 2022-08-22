<?php

namespace App\Widgets;

use App\Support\ForumBridge;
use App\TemplateEngine;

class MainMenu implements
    Widget
{
    private function items()
    {
        return [
            [
                "url" => "news",
                "name" => "News"
            ],
            [
                "url" => "features",
                "name" => "Über EtoA"
            ],
            [
                "url" => "screenshots",
                "name" => "Bilder"
            ],
            [
                "url" => "story",
                "name" => "Story"
            ],
            [
                "url" => "regeln",
                "name" => "Regeln"
            ],
            [
                "hr" => true
            ],
            [
                "url" => "register",
                "name" => "Mitspielen"
            ],
            [
                "url" => "pwrequest",
                "name" => "Passwort vergessen?"
            ],
            [
                "hr" => true
            ],
            [
                "url" => ForumBridge::url(), "name" => "Forum"
            ],
            !empty(get_config('ts_link')) ? [
                "url" => get_config('ts_link'), "name" => "Discord"
            ] : null,
            [
                "url" => 'archiv',
                "name" => "Downloads"
            ],
            [
                "url" => "https://github.com/etoa/etoa",
                "name" => "Entwicklung"
            ],
            [
                "hr" => true
            ],
            [
                "url" => "banner",
                "name" => "Weitersagen"
            ],
            [
                "url" => "spenden",
                "name" => "Spenden"
            ],
            [
                "url" => "disclaimer",
                "name" => "Disclaimer"
            ],
            [
                "url" => "privacy",
                "name" => "Datenschutz"
            ],
            [
                "url" => "impressum",
                "name" => "Impressum"
            ]
        ];
    }

    public function render(TemplateEngine $tpl): string
    {
        $tpl->assign('nav', array_filter($this->items(), fn ($i) => $i !== null));
        return $tpl->fetch('widgets/main-menu.html');
    }
}
