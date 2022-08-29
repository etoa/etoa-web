<?php

namespace App\Routing;

use App\Authentication\ForumAuthenticator;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\HttpBasicAuthentication;

class AppRouteProvider
{
    public function __invoke(RouteCollectorProxy $group)
    {
        $group->group('', FrontendRoutes::class);
        $group->group('/admin', BackendRoutes::class)
            ->add(new HttpBasicAuthentication([
                "realm" => "EtoA Login Administration",
                "authenticator" => new ForumAuthenticator,
                "before" => function (\Slim\Psr7\Request $request, array $arguments) {
                    return $request
                        ->withAttribute("user", $arguments["user"])
                        ->withAttribute("password", $arguments["password"]);
                },
                'secure' => false,
            ]));

        $group->any('/{path:.*}', PageNotFoundController::class);
    }
}
