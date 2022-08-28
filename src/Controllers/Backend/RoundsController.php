<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Service\RoundService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RoundsController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Runden';
    }

    function show(Request $request, Response $response, RoundService $rounds): Response
    {
        return parent::render($response, 'rounds.html', [
            'rounds' => $rounds->all(),
        ]);
    }

    function store(Request $request, Response $response, RoundService $rounds): Response
    {
        $post = $request->getParsedBody();
        if (isset($post['submit'])) {
            if (isset($post['round_name'])) {
                foreach ($post['round_name'] as $id => $v) {
                    if ($post['round_name'][$id] != "" && $post['round_url'][$id] != "") {
                        $rounds->update(
                            $id,
                            name: $post['round_name'][$id],
                            url: $post['round_url'][$id],
                            active: $post['round_active'][$id] == 1
                        );
                    }
                }
            }
            $deleted = 0;
            if (isset($post['round_del'])) {
                foreach ($post['round_del'] as $id => $delete) {
                    if ($delete == 1) {
                        $rounds->delete(intval($id));
                        $deleted++;
                    }
                }
            }
            $this->setSessionMessage('info', "Änderungen an den Runden gespeichert." . ($deleted > 0 ? "\n$deleted Runden entfernt." : ''));
        }
        if (isset($post['submit_new'])) {
            $rounds->create(name: '', url: '');
            $this->setSessionMessage('info', "Neue Runde hinzugefügt.");
        }

        return $this->redirectToNamedRoute($request, $response, 'admin.rounds');
    }
}
