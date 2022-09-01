<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\ConfigSettingRepository;
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
            'type' => 'text',
            'default' => 'orange',
            'required' => true,
        ],
    ];

    protected function getTitle(): string
    {
        return 'Servermeldung';
    }

    public function show(Request $request, Response $response, ConfigSettingRepository $config): Response
    {
        return parent::render($response, 'servernotice.html', [
            'settings' => collect(self::$settings)->map(fn ($def, $key) => [
                'name' => $key,
                'value' => $config->get($key, defaultValue: (string) $def['default'], useCache: false),
                'placeholder' => (string) $def['default'],
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
            if ($def['required'] && (!isset($post[$key]) || '' == trim($post[$key]))) {
                $this->setSessionMessage('error', "Das Feld '" . $def['label'] . "' darf nicht leer sein.");

                return $this->redirectToNamedRoute($request, $response, 'admin.servernotice');
            }
        }
        foreach (self::$settings as $key => $def) {
            $config->set($key, $post[$key]);
        }
        $config->setInt('server_notice_updated', time());
        $this->setSessionMessage('info', 'Einstellungen gespeichert.');

        return $this->redirectToNamedRoute($request, $response, 'admin.servernotice');
    }
}
