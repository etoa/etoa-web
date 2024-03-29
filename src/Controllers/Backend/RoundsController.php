<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\RoundRepository;
use Carbon\Carbon;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RoundsController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Runden';
    }

    public function index(Request $request, Response $response, RoundRepository $rounds): Response
    {
        return parent::render($response, 'rounds/index.html.twig', [
            'rounds' => $rounds->all(),
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        return parent::render($response, 'rounds/create.html.twig');
    }

    public function store(Request $request, Response $response, RoundRepository $rounds): Response
    {
        $post = $request->getParsedBody();

        $rounds->create(
            name: trim($post['name']),
            url: trim($post['url']),
            active: 1 == $post['active'],
            startDate: '' != trim($post['startDate']) ? Carbon::createFromDate($post['startDate'])->timestamp : 0,
        );

        $this->setSessionMessage('success', 'Runde hinzugefügt.');

        return $this->redirectToNamedRoute($request, $response, 'admin.rounds');
    }

    public function edit(Request $request, Response $response, RoundRepository $rounds, int $id): Response
    {
        return parent::render($response, 'rounds/edit.html.twig', [
            'round' => $rounds->get($id),
        ]);
    }

    public function update(Request $request, Response $response, RoundRepository $rounds, int $id): Response
    {
        $post = $request->getParsedBody();

        $rounds->update(
            $id,
            name: trim($post['name']),
            url: trim($post['url']),
            active: 1 == $post['active'],
            startDate: '' != trim($post['startDate']) ? Carbon::createFromDate($post['startDate'])->timestamp : 0,
        );

        $this->setSessionMessage('success', 'Runde gespeichert.');

        return $this->redirectToNamedRoute($request, $response, 'admin.rounds');
    }

    public function confirmDelete(Request $request, Response $response, RoundRepository $rounds, int $id): Response
    {
        return parent::render($response, 'rounds/delete.html.twig', [
            'round' => $rounds->get($id),
        ]);
    }

    public function destroy(Request $request, Response $response, RoundRepository $rounds, int $id): Response
    {
        $rounds->delete($id);

        $this->setSessionMessage('success', 'Runde gelöscht.');

        return $this->redirectToNamedRoute($request, $response, 'admin.rounds');
    }
}
