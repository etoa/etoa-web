<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\ConfigSettingRepository;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ServerNoticeController extends AbstractSettingsController
{
    protected function getSettings(): array
    {
        return [
            'server_notice' => [
                'label' => 'Servermeldung',
                'type' => 'textarea',
                'default' => '',
                'required' => false,
                'description' => 'Inhalt in BBCode',
            ],
            'server_notice_color' => [
                'label' => 'Farbe der Servermeldung',
                'type' => 'color',
                'default' => '#ff8000',
                'required' => true,
            ],
        ];
    }

    protected function getTitle(): string
    {
        return 'Servermeldung';
    }

    public function show(Request $request, Response $response, ConfigSettingRepository $config): Response
    {
        return parent::render($response, 'servernotice.html.twig', [
            'fields' => $this->getFields($config),
        ]);
    }

    public function store(Request $request, Response $response, ConfigSettingRepository $config, Logger $logger): Response
    {
        if (!($post = $this->storeSettings($request, $response, $config))) {
            return $this->redirectToNamedRoute($request, $response, 'admin.servernotice');
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
