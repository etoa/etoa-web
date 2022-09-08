<?php

declare(strict_types=1);

namespace App\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class AbstractController
{
    /**
     * @param array<string,mixed> $data
     * @param array<string,mixed> $queryParams
     */
    protected function redirectToNamedRoute(Request $request, Response $response, string $routeName, array $data = [], array $queryParams = []): Response
    {
        return $response
            ->withHeader('Location', RouteContext::fromRequest($request)->getRouteParser()->urlFor(
                $routeName,
                data: $data,
                queryParams: $queryParams
            ))
            ->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
}
