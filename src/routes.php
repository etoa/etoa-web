<?php

declare(strict_types=1);

use App\Authentication\ForumAuthenticator;
use App\Controllers\MaintenancePageController;
use App\Controllers\PageNotFoundController;
use App\Routing\BackendRoutes;
use App\Routing\FrontendRoutes;
use Tuupola\Middleware\HttpBasicAuthentication;

/** @var \Slim\App $app */

if (file_exists(__DIR__ . '/../storage/maintenance')) {
    $app->any('/{path:.*}', MaintenancePageController::class);
} else {
    $app->group('', FrontendRoutes::class);
    $app->group('/admin', BackendRoutes::class)
        ->add(new HttpBasicAuthentication([
            "realm" => "EtoA Login Administration",
            "authenticator" => new ForumAuthenticator,
            "before" => function (Slim\Psr7\Request $request, array $arguments) {
                return $request
                    ->withAttribute("user", $arguments["user"])
                    ->withAttribute("password", $arguments["password"]);
            },
            'secure' => false,
        ]));

    $app->any('/{path:.*}', PageNotFoundController::class);
}
