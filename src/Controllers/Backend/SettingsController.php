<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\ConfigSettingRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SettingsController extends BackendController
{
    private static array $settings = [
        'news_board' => [
            'label' => 'ID des News Forums',
            'type' => 'number',
            'default' => '',
            'required' => true,
        ],
        'news_posts_num' => [
            'label' => 'Anzahl News Posts auf der Startseite',
            'type' => 'number',
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
        'infobox_board_blacklist' => [
            'label' => 'Forum IDs welche nicht in der Infobox erscheinen sollen',
            'type' => 'text',
            'default' => '',
            'required' => false,
        ],
        'latest_posts_num' => [
            'label' => 'Anzahl der neusten Posts in der Infobox',
            'type' => 'number',
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
        'forum_mail' => [
            'label' => 'Forum E-Mail',
            'type' => 'email',
            'default' => '',
            'required' => false,
        ],
        'forum_url' => [
            'label' => 'Forum URL',
            'type' => 'url',
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

    protected function getTitle(): string
    {
        return 'Einstellungen';
    }

    public function show(Request $request, Response $response, ConfigSettingRepository $config): Response
    {
        return parent::render($response, 'settings.html', [
            'settings' => collect(self::$settings)->map(fn ($def, $key) => [
                'name' => $key,
                'value' =>  $config->get($key, defaultValue: (string)$def['default'], useCache: false),
                'placeholder' => (string)$def['default'],
                'label' => $def['label'],
                'type' => $def['type'],
                'required' => $def['required'],
            ])->toArray(),
        ]);
    }

    public function store(Request $request, Response $response, ConfigSettingRepository $config): Response
    {
        $post = $request->getParsedBody();
        foreach (self::$settings as $key => $def) {
            if ($def['required'] && (!isset($post[$key]) || trim($post[$key]) == '')) {
                $this->setSessionMessage('error', "Das Feld '" . $def['label'] . "' darf nicht leer sein.");
                return $this->redirectToNamedRoute($request, $response, 'admin.settings');
            }
        }
        foreach (self::$settings as $key => $def) {
            $config->set($key, $post[$key]);
        }
        $this->setSessionMessage('info', "Einstellungen gespeichert.");

        return $this->redirectToNamedRoute($request, $response, 'admin.settings');
    }
}
