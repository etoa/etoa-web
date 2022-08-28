<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Service\ConfigService;
use App\Service\RoundService;
use App\Service\TextService;
use App\Support\BBCodeConverter;
use App\Widgets\GameLogin;
use App\Widgets\InfoBox;
use App\Widgets\MainMenu;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class FrontendController
{
    function __construct(
        protected Twig $view,
        private RoundService $rounds,
        private TextService $texts,
        protected ConfigService $config,
    ) {
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
                'votebanner' => $this->config->get('buttons'),
                'adds' => $this->config->get('adds'),
                'footerJs' => $this->config->get('footer_js'),
                'headerJs' => $this->config->get('indexjscript'),
                'mainMenu' => (new MainMenu($this->config))->render($this->view),
                'gameLogin' => (new GameLogin($this->rounds))->render($this->view),
                'infobox' => (new InfoBox($this->config))->render($this->view),
            ], $args)
        );
    }

    protected function getTextContent(string $keyword): string
    {
        $text = $this->texts->findByKeyword($keyword);
        if ($text !== null) {
            if ($text->content != "") {
                return BBCodeConverter::toHtml($text->content);
            }
            return "<p><i><b>Fehler:</b> Texteintrag fehlt!</i></p>";
        } else {
            return "<p><i><b>Fehler:</b> Datensatz fehlt!</i></p>";
        }
    }
}
