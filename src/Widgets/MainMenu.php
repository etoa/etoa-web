<?php

namespace App\Widgets;

use App\Support\ForumBridge;
use Slim\Views\Twig;

class MainMenu implements Widget
{
    private function items()
    {
        return [
            [
                'type' => 'route',
                'route' => "news",
                'label' => "News"
            ],
            [
                'type' => 'route',
                'route' => "features",
                'label' => "Über EtoA"
            ],
            [
                'type' => 'route',
                'route' => "screenshots",
                'label' => "Bilder"
            ],
            [
                'type' => 'route',
                'route' => "story",
                'label' => "Story"
            ],
            [
                'type' => 'route',
                'route' => "rules",
                'label' => "Regeln"
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'route',
                'route' => "register",
                'label' => "Mitspielen"
            ],
            [
                'type' => 'route',
                'route' => "pwrequest",
                'label' => "Passwort vergessen?"
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'url',
                "url" => ForumBridge::url(),
                'label' => "Forum",
            ],
            !empty(get_config('ts_link')) ? [
                'type' => 'url',
                "url" => get_config('ts_link'),
                'label' => "Discord"
            ] : null,
            [
                'type' => 'url',
                "url" => 'archiv',
                'label' => "Downloads"
            ],
            [
                'type' => 'url',
                "url" => "https://github.com/etoa/etoa",
                'label' => "Entwicklung"
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'route',
                'route' => "banner",
                'label' => "Weitersagen"
            ],
            [
                'type' => 'route',
                'route' => "donate",
                'label' => "Spenden"
            ],
            [
                'type' => 'route',
                'route' => "disclaimer",
                'label' => "Disclaimer"
            ],
            [
                'type' => 'route',
                'route' => "privacy",
                'label' => "Datenschutz"
            ],
            [
                'type' => 'route',
                'route' => "imprint",
                'label' => "Impressum"
            ]
        ];
    }

    public function render(Twig $view): string
    {
        return $view->fetch('widgets/main-menu.html', [
            'nav' => array_filter($this->items(), fn ($i) => $i !== null),
        ]);
    }
}
