<?php

declare(strict_types=1);

namespace App\Controllers\Frontend\Widgets;

use App\Repository\ConfigSettingRepository;
use App\Support\ForumBridge;
use Slim\Views\Twig;

class MainMenu implements Widget
{
    function __construct(private ConfigSettingRepository $config)
    {
    }

    private function items()
    {
        $tsLink = $this->config->get('ts_link');
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
            !empty($tsLink) ? [
                'type' => 'url',
                "url" => $tsLink,
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
        return $view->fetch('frontend/widgets/main-menu.html', [
            'nav' => array_filter($this->items(), fn ($i) => $i !== null),
        ]);
    }
}
