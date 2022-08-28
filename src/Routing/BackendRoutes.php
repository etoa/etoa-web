<?php

namespace App\Routing;

use App\Controllers\Backend\OverviewController;
use App\Controllers\Backend\ServerInfoController;
use Slim\Routing\RouteCollectorProxy;

class BackendRoutes
{
    public function __invoke(RouteCollectorProxy $group)
    {
        $group->get('', OverviewController::class)
            ->setName('admin');
        $group->get('/serverinfo', [ServerInfoController::class, 'show'])
            ->setName('admin.serverinfo');
        $group->post('/serverinfo', [ServerInfoController::class, 'store'])
            ->setName('admin.serverinfo.store');
    }
}
