<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class HttpsRedirectMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (!in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1'])) {
            if (empty($_SERVER['HTTPS']) || 'off' === $_SERVER['HTTPS']) {
                $location = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: '.$location);
                exit;
            }
        }

        return $handler->handle($request);
    }
}
