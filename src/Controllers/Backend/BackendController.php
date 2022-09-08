<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Controllers\AbstractController;
use App\Support\ForumBridge;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class BackendController extends AbstractController
{
    /**
     * @return array<int,array<string,string>>
     */
    private static function mainMenu(): array
    {
        return [
            [
                'type' => 'route',
                'route' => 'admin',
                'label' => 'Übersicht',
            ],
            [
                'type' => 'route',
                'route' => 'admin.servernotice',
                'label' => 'Servermeldung',
            ],
            [
                'type' => 'route',
                'route' => 'admin.rounds',
                'label' => 'Runden',
            ],
            [
                'type' => 'route',
                'route' => 'admin.redirects',
                'label' => 'Weiterleitungen',
            ],
            [
                'type' => 'route',
                'route' => 'admin.texts',
                'label' => 'Texte',
            ],
            [
                'type' => 'route',
                'route' => 'admin.files',
                'label' => 'Dateien',
            ],
            [
                'type' => 'route',
                'route' => 'admin.settings',
                'label' => 'Einstellungen',
            ],
        ];
    }

    /**
     * @return array<int,array<string,string>>
     */
    private static function secondaryMenu(): array
    {
        return [
            [
                'type' => 'route',
                'route' => 'home',
                'label' => 'Startseite',
                'target' => '_blank',
            ],
            [
                'type' => 'url',
                'url' => ForumBridge::url(),
                'label' => 'Forum',
                'target' => '_blank',
            ],
        ];
    }

    public function __construct(protected Twig $view, protected \SlimSession\Helper $session)
    {
    }

    abstract protected function getTitle(): string;

    /**
     * @param array<string,mixed> $args
     */
    protected function render(Response $response, string $backendTemplate, array $args = []): Response
    {
        return $this->view->render(
            $response,
            'backend/' . $backendTemplate,
            array_merge([
                'title' => $this->getTitle(),
                'info' => $this->pullSessionMessage('info'),
                'error' => $this->pullSessionMessage('error'),
                'success' => $this->pullSessionMessage('success'),
                'nav' => self::mainMenu(),
                'nav2' => self::secondaryMenu(),
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
