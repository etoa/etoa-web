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
        return parent::render($response, 'rounds/index.html', [
            'rounds' => $rounds->all(),
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        return parent::render($response, 'rounds/create.html');
    }

    public function store(Request $request, Response $response, RoundRepository $rounds): Response
    {
        $post = $request->getParsedBody();

        $rounds->create(
            name: $post['name'],
            url: $post['url'],
            active: 1 == $post['active'],
            startDate: '' != trim($post['startDate']) ? Carbon::createFromDate($post['startDate'])->timestamp : 0,
        );

        $this->setSessionMessage('info', 'Runde hinzugefügt.');

        return $this->redirectToNamedRoute($request, $response, 'admin.rounds');
    }

    public function edit(Request $request, Response $response, RoundRepository $rounds, int $id): Response
    {
        return parent::render($response, 'rounds/edit.html', [
            'round' => $rounds->get($id),
        ]);
    }

    public function update(Request $request, Response $response, RoundRepository $rounds, int $id): Response
    {
        $post = $request->getParsedBody();

        $rounds->update(
            $id,
            name: $post['name'],
            url: $post['url'],
            active: 1 == $post['active'],
            startDate: '' != trim($post['startDate']) ? Carbon::createFromDate($post['startDate'])->timestamp : 0,
        );

        $this->setSessionMessage('info', 'Runde gespeichert.');

        return $this->redirectToNamedRoute($request, $response, 'admin.rounds');
    }

    public function confirmDelete(Request $request, Response $response, RoundRepository $rounds, int $id): Response
    {
        return parent::render($response, 'rounds/delete.html', [
            'round' => $rounds->get($id),
        ]);
    }

    public function destroy(Request $request, Response $response, RoundRepository $rounds, int $id): Response
    {
        $this->setSessionMessage('info', 'Runde gelöscht.');

        $rounds->delete($id);

        return $this->redirectToNamedRoute($request, $response, 'admin.rounds');
    }
}
