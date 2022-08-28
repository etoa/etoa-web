<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Service\ConfigService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class SettingsController extends BackendController
{
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
        ]);
    }

    function store(Request $request, Response $response, ConfigService $config)
    {
        $config->set('server_notice', $request->getParsedBody()['server_notice']);
        $config->set('server_notice_updated', (string)time());
        $config->set('server_notice_color', $request->getParsedBody()['server_notice_color']);
        $config->set('adds', $request->getParsedBody()['adds']);

        $this->setSessionMessage('info', "Gespeichert!");

        return $response
            ->withHeader('Location', RouteContext::fromRequest($request)->getRouteParser()->urlFor('admin.settings'))
            ->withStatus(302);
    }
}
