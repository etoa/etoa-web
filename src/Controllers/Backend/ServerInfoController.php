<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class ServerInfoController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Servermeldung';
    }

    function show(Request $request, Response $response): Response
    {
        return parent::render($response, 'serverinfo.html', [
            'server_notice' => get_config('server_notice', ''),
            'server_notice_color' => get_config('server_notice_color', 'orange'),
        ]);
    }

    function store(Request $request, Response $response)
    {
        set_config('server_notice', $request->getParsedBody()['server_notice']);
        set_config('server_notice_updated', time());
        set_config('server_notice_color', $request->getParsedBody()['server_notice_color']);

        $this->setSessionMessage('info', "Gespeichert!");

        return $response
            ->withHeader('Location', RouteContext::fromRequest($request)->getRouteParser()->urlFor('admin.serverinfo'))
            ->withStatus(302);
    }
}
