<?php

namespace App\Routing;

use App\Controllers\Backend\OverviewController;
use Slim\Routing\RouteCollectorProxy;

class BackendRoutes
{
    public function __invoke(RouteCollectorProxy $group)
    {
        $group->get('', OverviewController::class)
            ->setName('admin');
    }
}
