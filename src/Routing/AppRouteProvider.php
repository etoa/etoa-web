<?php

declare(strict_types=1);

namespace App\Routing;

use App\Authentication\ForumAuthenticator;
use App\Controllers\MaintenancePageController;
use App\Controllers\PageNotFoundController;
use App\Controllers\UnauthorizedRequestController;
use App\Repository\RedirectRepository;
use DI\Container;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\HttpBasicAuthentication;

class AppRouteProvider
{
    private const CACHE_FILE = CACHE_DIR . '/routes.cache';
    public const MAINTENANCE_PAGE_TRIGGER = STORAGE_DIR . '/maintenance';

    public function __construct(private Container $container, private bool $caching = false)
    {
    }

    public function __invoke(RouteCollectorProxy $group): void
    {
        if (file_exists(self::MAINTENANCE_PAGE_TRIGGER)) {
            $group->any('/{path:.*}', MaintenancePageController::class);
        } else {
            $group->group('', FrontendRoutes::class);

            $group->group('/admin', BackendRoutes::class)
                ->add($this->getBasicAuth());

            /** @var RedirectRepository $redirects */
            $redirects = $this->container->get(RedirectRepository::class);
            foreach ($redirects->active() as $redirect) {
                $group->redirect($redirect->source, $redirect->target);
            }

            $group->any('/{path:.*}', PageNotFoundController::class);

            if ($this->caching) {
                $routeCollector = $group->getRouteCollector();
                $routeCollector->setCacheFile(self::CACHE_FILE);
            }
        }
    }

    private function getBasicAuth(): MiddlewareInterface
    {
        return new HttpBasicAuthentication([
            'realm' => 'EtoA Login Administration',
            'authenticator' => $this->container->get(ForumAuthenticator::class),
            'secure' => true,
            'error' => $this->container->get(UnauthorizedRequestController::class),
        ]);
    }

    public static function clearCache(): void
    {
        if (is_file(self::CACHE_FILE)) {
            unlink(self::CACHE_FILE);
        }
    }
}
