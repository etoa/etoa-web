<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Support\ForumBridge;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

abstract class BackendController
{
    private const MAIN_MENU = [
        [
            'type' => 'route',
            'route' => 'admin',
            'label' => 'Übersicht',
        ],
        [
            'type' => 'route',
            'route' => 'admin.servernotice',
            'label' => 'Servermeldung',
        ],
        [
            'type' => 'route',
            'route' => 'admin.rounds',
            'label' => 'Runden',
        ],
        [
            'type' => 'route',
            'route' => 'admin.redirects',
            'label' => 'Weiterleitungen',
        ],
        [
            'type' => 'route',
            'route' => 'admin.texts',
            'label' => 'Texte',
        ],
        [
            'type' => 'route',
            'route' => 'admin.settings',
            'label' => 'Einstellungen',
        ],
    ];

    private static function secondaryMenu(): array
    {
        return [
            [
                'type' => 'route',
                'route' => 'home',
                'label' => 'Startseite',
            ],
            [
                'type' => 'url',
                'url' => ForumBridge::url(),
                'label' => 'Forum',
            ],
        ];
    }

    public function __construct(protected Twig $view, protected \SlimSession\Helper $session)
    {
    }

    abstract protected function getTitle(): string;

    /**
     * @param array<string,mixed> $args
     */
    protected function render(Response $response, string $backendTemplate, array $args = []): Response
    {
        return $this->view->render(
            $response,
            'backend/' . $backendTemplate,
            array_merge([
                'title' => $this->getTitle(),
                'info' => $this->pullSessionMessage('info'),
                'error' => $this->pullSessionMessage('error'),
                'success' => $this->pullSessionMessage('success'),
                'nav' => self::MAIN_MENU,
                'nav2' => self::secondaryMenu(),
            ], $args)
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    protected function redirectToNamedRoute(Request $request, Response $response, string $routeName, array $data = []): Response
    {
        return $response
            ->withHeader('Location', RouteContext::fromRequest($request)->getRouteParser()->urlFor($routeName, data: $data))
            ->withStatus(302);
    }

    protected function setSessionMessage(string $key, string $value): void
    {
        $this->session->set($key, $value);
    }

    private function pullSessionMessage(string $key): ?string
    {
        if (!$this->session->exists($key)) {
            return null;
        }

        $value = $this->session->get($key);
        $this->session->delete($key);

        return $value;
    }
}
