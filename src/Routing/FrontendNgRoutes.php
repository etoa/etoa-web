<?php

declare(strict_types=1);

namespace App\Routing;

use App\Controllers\FrontendNg\HomeController;
use Slim\Routing\RouteCollectorProxy;

class FrontendNgRoutes
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get('', HomeController::class)
            ->setName('ng.home');
    }
}
