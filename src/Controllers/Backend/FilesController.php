<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FilesController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Dateimanager';
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return parent::render($response, 'files.html', []);
    }
}
