<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

abstract class BackendController
{
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
