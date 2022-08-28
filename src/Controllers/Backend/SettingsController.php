<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Service\ConfigService;
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
        'loginadmin_group' => [
            'label' => 'Usergruppe für Login Administration',
            'type' => 'number',
            'default' => '',
            'required' => true,
        ],
        'infobox_board_blacklist' => [
            'label' => 'Forum IDs welche nicht in der Infobox erscheinen sollen',
            'type' => 'text',
            'default' => '',
            'required' => false,
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
    ];

    protected function getTitle(): string
    {
        return 'Einstellungen';
    }

    function show(Request $request, Response $response, ConfigService $config): Response
    {

        return parent::render($response, 'settings.html', [
            'server_notice' => $config->get('server_notice'),
            'server_notice_color' => $config->get('server_notice_color', 'orange'),
            'adds' => $config->get('adds', ''),
            'indexjscript' => $config->get('indexjscript', ''),
            'footer_js' => $config->get('footer_js', ''),
            'buttons' => $config->get('buttons', ''),
            'settings' => collect(self::$settings)->map(fn ($def, $key) => [
                'name' => $key,
                'value' =>  $config->get($key, $def['default']),
                'label' => $def['label'],
                'type' => $def['type'],
                'required' => $def['required'],
            ])->toArray(),
        ]);
    }

    function store(Request $request, Response $response, ConfigService $config): Response
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

        $config->set('server_notice', $post['server_notice']);
        $config->set('server_notice_updated', (string)time());
        $config->set('server_notice_color', $post['server_notice_color']);
        $config->set('adds', $post['adds']);
        $config->set('indexjscript', $post['indexjscript']);
        $config->set('footer_js', $post['footer_js']);
        $config->set('buttons', $post['buttons']);


        $this->setSessionMessage('info', "Einstellungen gespeichert.");

        return $this->redirectToNamedRoute($request, $response, 'admin.settings');
    }
}
