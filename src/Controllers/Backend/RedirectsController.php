<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\RedirectRepository;
use App\Routing\AppRouteProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

class RedirectsController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Weiterleitungen';
    }

    public function index(Request $request, Response $response, RedirectRepository $redirects): Response
    {
        return parent::render($response, 'redirects/index.html', [
            'redirects' => $redirects->all(),
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        return parent::render($response, 'redirects/create.html');
    }

    public function store(Request $request, Response $response, RedirectRepository $redirects, App $app): Response
    {
        $post = $request->getParsedBody();

        $required = [
            'source' => 'Quelle',
            'target' => 'Ziel',
        ];
        foreach ($required as $key => $label) {
            if (!isset($post[$key]) || '' == trim($post[$key])) {
                $this->setSessionMessage('error', "Das Feld '$label' darf nicht leer sein.");

                return $this->redirectToNamedRoute($request, $response, 'admin.redirects.create');
            }
        }

        $routes = $app->getRouteCollector()->getRoutes();
        if (count(array_filter($routes, fn ($route) => $route->getPattern() == $post['source'])) > 0) {
            $this->setSessionMessage('error', "Die Quelle '" . $post['source'] . "' ist bereits in Verwendung.");

            return $this->redirectToNamedRoute($request, $response, 'admin.redirects.create');
        }

        $redirects->create(
            source: trim($post['source']),
            target: trim($post['target']),
            active: 1 == $post['active']
        );

        AppRouteProvider::clearCache();

        $this->setSessionMessage('success', 'Weiterleitung hinzugefügt.');

        return $this->redirectToNamedRoute($request, $response, 'admin.redirects');
    }

    public function edit(Request $request, Response $response, RedirectRepository $redirects, int $id): Response
    {
        return parent::render($response, 'redirects/edit.html', [
            'redirect' => $redirects->get($id),
        ]);
    }

    public function update(Request $request, Response $response, RedirectRepository $redirects, int $id): Response
    {
        $post = $request->getParsedBody();

        $required = [
            'target' => 'Ziel',
        ];
        foreach ($required as $key => $label) {
            if (!isset($post[$key]) || '' == trim($post[$key])) {
                $this->setSessionMessage('error', "Das Feld '$label' darf nicht leer sein.");

                return $this->redirectToNamedRoute($request, $response, 'admin.redirects.edit', ['id' => $id]);
            }
        }

        $redirects->update(
            $id,
            target: trim($post['target']),
            active: 1 == $post['active'],
        );

        AppRouteProvider::clearCache();

        $this->setSessionMessage('success', 'Weiterleitung gespeichert.');

        return $this->redirectToNamedRoute($request, $response, 'admin.redirects');
    }

    public function confirmDelete(Request $request, Response $response, RedirectRepository $redirects, int $id): Response
    {
        return parent::render($response, 'redirects/delete.html', [
            'redirect' => $redirects->get($id),
        ]);
    }

    public function destroy(Request $request, Response $response, RedirectRepository $redirects, int $id): Response
    {
        $redirects->delete($id);

        AppRouteProvider::clearCache();

        $this->setSessionMessage('success', 'Weiterleitung gelöscht.');

        return $this->redirectToNamedRoute($request, $response, 'admin.redirects');
    }
}
