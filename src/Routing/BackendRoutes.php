<?php

namespace App\Routing;

use App\Controllers\Backend\OverviewController;
use App\Controllers\Backend\SettingsController;
use Slim\Routing\RouteCollectorProxy;

class BackendRoutes
{
    public function __invoke(RouteCollectorProxy $group)
    {
        $group->get('', OverviewController::class)
            ->setName('admin');
        $group->get('/settings', [SettingsController::class, 'show'])
            ->setName('admin.settings');
        $group->post('/settings', [SettingsController::class, 'store'])
            ->setName('admin.settings.store');
    }
}
