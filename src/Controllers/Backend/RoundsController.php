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

    public function show(Request $request, Response $response, RoundRepository $rounds): Response
    {
        return parent::render($response, 'rounds.html', [
            'rounds' => $rounds->all(),
        ]);
    }

    public function store(Request $request, Response $response, RoundRepository $rounds): Response
    {
        $post = $request->getParsedBody();
        if (isset($post['submit'])) {
            if (isset($post['name'])) {
                foreach ($post['name'] as $id => $v) {
                    if ('' != $post['name'][$id] && '' != $post['url'][$id]) {
                        $rounds->update(
                            $id,
                            name: $post['name'][$id],
                            url: $post['url'][$id],
                            active: 1 == $post['active'][$id],
                            startDate: '' != trim($post['startDate'][$id]) ? Carbon::createFromDate($post['startDate'][$id])->timestamp : 0,
                        );
                    }
                }
            }
            $deleted = 0;
            if (isset($post['delete'])) {
                foreach ($post['delete'] as $id => $delete) {
                    if (1 == $delete) {
                        $rounds->delete(intval($id));
                        ++$deleted;
                    }
                }
            }
            $this->setSessionMessage('info', 'Änderungen an den Runden gespeichert.' . ($deleted > 0 ? "\n$deleted Runden entfernt." : ''));
        }
        if (isset($post['submit_new'])) {
            $rounds->create(name: '', url: '');
            $this->setSessionMessage('info', 'Neue Runde hinzugefügt.');
        }

        return $this->redirectToNamedRoute($request, $response, 'admin.rounds');
    }
}
