<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Widgets\GameLogin;
use App\Widgets\InfoBox;
use App\Widgets\MainMenu;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class FrontendController
{
    function __construct(protected Twig $view)
    {
    }

    protected abstract function getTitle(): string;
    protected abstract function getHeaderImage(): string;

    protected function getSiteTitle(): ?string
    {
        return null;
    }

    protected function render(Response $response, string $frontendTemplate, array $args): Response
    {
        return $this->view->render(
            $response,
            'frontend/' . $frontendTemplate,
            array_merge([
                'title' => $this->getTitle(),
                'site_title' => $this->getSiteTitle(),
                'header_img' => $this->getHeaderImage(),
                'votebanner' => get_config('buttons'),
                'adds' => get_config('adds'),
                'footerJs' => get_config('footer_js'),
                'headerJs' => get_config('indexjscript'),
                'mainMenu' => (new MainMenu())->render($this->view),
                'gameLogin' => (new GameLogin())->render($this->view),
                'infobox' => (new InfoBox())->render($this->view),
            ], $args)
        );
    }
}
