<?php

declare(strict_types=1);

namespace App\Routing;

use App\Controllers\Backend\OverviewController;
use App\Controllers\Backend\RoundsController;
use App\Controllers\Backend\ServerNoticeController;
use App\Controllers\Backend\SettingsController;
use App\Controllers\Backend\TextController;
use Slim\Routing\RouteCollectorProxy;

class BackendRoutes
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get('', OverviewController::class)
            ->setName('admin');

        $group->get('/servernotice', [ServerNoticeController::class, 'show'])
            ->setName('admin.servernotice');
        $group->post('/servernotice', [ServerNoticeController::class, 'store'])
            ->setName('admin.servernotice.store');

        $group->get('/settings', [SettingsController::class, 'show'])
            ->setName('admin.settings');
        $group->post('/settings', [SettingsController::class, 'store'])
            ->setName('admin.settings.store');

        $group->get('/rounds', [RoundsController::class, 'index'])
            ->setName('admin.rounds');
        $group->get('/rounds/create', [RoundsController::class, 'create'])
            ->setName('admin.rounds.create');
        $group->post('/rounds/create', [RoundsController::class, 'store'])
            ->setName('admin.rounds.store');
        $group->get('/rounds/{id:[0-9]+}', [RoundsController::class, 'edit'])
            ->setName('admin.rounds.edit');
        $group->post('/rounds/{id:[0-9]+}', [RoundsController::class, 'update'])
            ->setName('admin.rounds.update');
        $group->get('/rounds/{id:[0-9]+}/delete', [RoundsController::class, 'confirmDelete'])
            ->setName('admin.rounds.confirmDelete');
        $group->post('/rounds/{id:[0-9]+}/delete', [RoundsController::class, 'destroy'])
            ->setName('admin.rounds.destroy');

        $group->get('/texts', [TextController::class, 'index'])
            ->setName('admin.texts');
        $group->get('/texts/{id:[0-9]+}', [TextController::class, 'edit'])
            ->setName('admin.texts.edit');
        $group->post('/texts/{id:[0-9]+}', [TextController::class, 'update'])
            ->setName('admin.texts.update');
    }
}
