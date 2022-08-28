<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class BackendController
{
    function __construct(protected Twig $view, protected \SlimSession\Helper $session)
    {
    }

    protected abstract function getTitle(): string;

    protected function render(Response $response, string $backendTemplate, array $args): Response
    {
        return $this->view->render(
            $response,
            'backend/' . $backendTemplate,
            array_merge([
                'title' => $this->getTitle(),
                'info' => $this->pullSessionMessage('info'),
                'error' => $this->pullSessionMessage('error'),
            ], $args)
        );
    }

    protected function setSessionMessage(string $key, string $value): void
    {
        $this->session->set($key, $value);
    }

    private function pullSessionMessage(string $key): ?string
    {
        if (!$this->session->exists($key)) {
            return null;
        }

        $value = $this->session->get($key);
        $this->session->delete($key);

        return $value;
    }
}
