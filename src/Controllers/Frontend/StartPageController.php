<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Controllers\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StartPageController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $page = $request->getQueryParams()['page'] ?? null;
        $err = $request->getQueryParams()['err'] ?? null;
        if ($page == 'err' && $err !== null) {
            return $this->redirectToNamedRoute($request, $response, 'error', queryParams: ['err' => $err]);
        }

        return $this->redirectToNamedRoute($request, $response, 'news');
    }
}
