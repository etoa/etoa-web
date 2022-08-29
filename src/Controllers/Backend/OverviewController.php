<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\ConfigSettingRepository;
use App\Repository\RoundRepository;
use App\Support\ForumBridge;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OverviewController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Übersicht';
    }

    function __invoke(Request $request, Response $response, RoundRepository $rounds, ConfigSettingRepository $config, Container $container): Response
    {
        // print_r($container->get('settings'));

        $admins = ForumBridge::usersOfGroup($config->getInt('loginadmin_group'));

        return parent::render($response, 'overview.html', [
            'forumAdminUrl' => ForumBridge::url('admin'),
            'rounds' => $rounds->all(),
            'admins' => array_map(fn ($admin) => [
                'name' => $admin['username'],
                'url' => ForumBridge::url('user', $admin['id']),
            ], $admins),
        ]);
    }
}
