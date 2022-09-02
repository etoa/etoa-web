<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\TextRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TextController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Text bearbeiten';
    }

    public function index(Request $request, Response $response, TextRepository $texts): Response
    {
        return parent::render($response, 'texts/index.html', [
            'texts' => $texts->all(),
        ]);
    }

    public function edit(Request $request, Response $response, TextRepository $texts, int $id): Response
    {
        return parent::render($response, 'texts/edit.html', [
            'text' => $texts->get($id),
        ]);
    }

    public function update(Request $request, Response $response, TextRepository $texts, int $id): Response
    {
        $post = $request->getParsedBody();

        $texts->update($id, (string) $post['content']);

        $this->setSessionMessage('success', 'Text gespeichert.');

        return $this->redirectToNamedRoute($request, $response, 'admin.texts');
    }
}
