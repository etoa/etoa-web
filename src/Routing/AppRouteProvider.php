<?php

declare(strict_types=1);

namespace App\Routing;

use App\Authentication\ForumAuthenticator;
use App\Controllers\MaintenancePageController;
use App\Controllers\PageNotFoundController;
use DI\Container;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\HttpBasicAuthentication;

class AppRouteProvider
{
    public const CACHE_FILE = APP_DIR . '/storage/cache/routes';

    public function __construct(private Container $container, private bool $debug = false)
    {
    }

    public function __invoke(RouteCollectorProxy $group): void
    {
        if (file_exists(APP_DIR . '/storage/maintenance')) {
            $group->any('/{path:.*}', MaintenancePageController::class);
        } else {
            $group->group('', FrontendRoutes::class);
            $group->group('/admin', BackendRoutes::class)
                ->add($this->getBasicAuth());
            $group->any('/{path:.*}', PageNotFoundController::class);

            if (!$this->debug) {
                $routeCollector = $group->getRouteCollector();
                $routeCollector->setCacheFile(self::CACHE_FILE);
            }
        }
    }

    private function getBasicAuth(): MiddlewareInterface
    {
        return new HttpBasicAuthentication([
            "realm" => "EtoA Login Administration",
            "authenticator" => $this->container->get(ForumAuthenticator::class),
            'secure' => true,
        ]);
    }
}
