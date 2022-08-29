<?php

namespace App\Routing;

use App\Authentication\ForumAuthenticator;
use App\Controllers\MaintenancePageController;
use App\Controllers\PageNotFoundController;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\HttpBasicAuthentication;

class AppRouteProvider
{
    public function __invoke(RouteCollectorProxy $group)
    {
        if (file_exists(APP_DIR . '/storage/maintenance')) {
            $group->any('/{path:.*}', MaintenancePageController::class);
        } else {
            $group->group('', FrontendRoutes::class);
            $group->group('/admin', BackendRoutes::class)
                ->add($this->getBasicAuth());
            $group->any('/{path:.*}', PageNotFoundController::class);
        }
    }

    private function getBasicAuth(): MiddlewareInterface
    {
        return new HttpBasicAuthentication([
            "realm" => "EtoA Login Administration",
            "authenticator" => new ForumAuthenticator,
            'secure' => true,
        ]);
    }
}
