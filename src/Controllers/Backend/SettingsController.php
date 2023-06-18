<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\ConfigSettingRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SettingsController extends AbstractSettingsController
{
    protected function getSettings(): array
    {
        return [
            'news_board' => [
                'label' => 'ID des News Forums',
                'type' => 'number',
                'default' => '',
                'required' => true,
            ],
            'news_posts_num' => [
                'label' => 'Anzahl News Posts auf der Startseite',
                'type' => 'number',
                'min' => 1,
                'default' => 3,
                'required' => true,
            ],
            'rules_board' => [
                'label' => 'ID des Regeln Forums',
                'type' => 'number',
                'default' => '',
                'required' => true,
            ],
            'rules_thread' => [
                'label' => 'ID des Forumthemas über die Regeln',
                'type' => 'number',
                'default' => '',
                'required' => true,
            ],
            'ts_link' => [
                'label' => 'Discord URL',
                'type' => 'url',
                'default' => '',
                'required' => false,
            ],
            'latest_posts_num' => [
                'label' => 'Anzahl der neusten Posts in der Infobox',
                'type' => 'number',
                'min' => 1,
                'default' => 5,
                'required' => true,
            ],
            'status_board' => [
                'label' => 'ID des Status Forums',
                'type' => 'number',
                'default' => '',
                'required' => true,
            ],
            'support_board' => [
                'label' => 'ID des Support Forums',
                'type' => 'number',
                'default' => '',
                'required' => true,
            ],
            'adds' => [
                'label' => 'HTML Code für reches Vertikalbanner',
                'type' => 'textarea',
                'default' => '',
                'required' => false,
            ],
            'indexjscript' => [
                'label' => 'HTML Code für Header',
                'type' => 'textarea',
                'default' => '',
                'required' => false,
            ],
            'footer_js' => [
                'label' => 'HTML Code für Footer',
                'type' => 'textarea',
                'default' => '',
                'required' => false,
            ],
            'buttons' => [
                'label' => 'HTML Code für Buttons',
                'type' => 'textarea',
                'default' => '',
                'required' => false,
            ],
        ];
    }

    protected function getTitle(): string
    {
        return 'Einstellungen';
    }

    public function show(Request $request, Response $response, ConfigSettingRepository $config): Response
    {
        return parent::render($response, 'settings.html.twig', [
            'fields' => $this->getFields($config),
        ]);
    }

    public function store(Request $request, Response $response, ConfigSettingRepository $config): Response
    {
        if (!($post = $this->storeSettings($request, $response, $config))) {
            return $this->redirectToNamedRoute($request, $response, 'admin.settings');
        }

        $this->setSessionMessage('success', 'Einstellungen gespeichert.');

        return $this->redirectToNamedRoute($request, $response, 'admin.settings');
    }
}
