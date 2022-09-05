<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\ConfigSettingRepository;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ServerNoticeController extends BackendController
{
    /**
     * @var array<string,array<string,mixed>>
     */
    private static array $settings = [
        'server_notice' => [
            'label' => 'Servermeldung (BBCode)',
            'type' => 'textarea',
            'default' => '',
            'required' => false,
        ],
        'server_notice_color' => [
            'label' => 'Farbe der Servermeldung',
            'type' => 'color',
            'default' => '#ff8000',
            'required' => true,
        ],
    ];

    protected function getTitle(): string
    {
        return 'Servermeldung';
    }

    public function show(Request $request, Response $response, ConfigSettingRepository $config): Response
    {
        $fields = [];
        foreach (self::$settings as $key => $def) {
            $fields[$key] = [
                ...$def,
                'name' => $key,
                'value' => $config->get($key, defaultValue: (string) $def['default'], useCache: false),
                'placeholder' => (string) $def['default'],
            ];
        }

        return parent::render($response, 'servernotice.html', [
            'fields' => $fields,
        ]);
    }

    public function store(Request $request, Response $response, ConfigSettingRepository $config, Logger $logger): Response
    {
        $post = $request->getParsedBody();
        foreach (self::$settings as $key => $def) {
            if ($def['required'] && (!isset($post[$key]) || '' == trim($post[$key]))) {
                $this->setSessionMessage('error', "Das Feld '" . $def['label'] . "' darf nicht leer sein.");

                return $this->redirectToNamedRoute($request, $response, 'admin.servernotice');
            }
        }
        foreach (self::$settings as $key => $def) {
            $config->set($key, $post[$key]);
        }
        if ('' != $post['server_notice']) {
            $config->setInt('server_notice_updated', time());
            $logger->info('Updated server notice.', [
                'content' => $post['server_notice'],
            ]);
        }

        $this->setSessionMessage('success', 'Einstellungen gespeichert.');

        return $this->redirectToNamedRoute($request, $response, 'admin.servernotice');
    }
}
