<?php

namespace App\Controllers\FrontendNg;

use App\Controllers\AbstractController;
use App\Repository\TextRepository;
use App\Support\BBCodeConverter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class AboutController extends AbstractController
{
    public function __construct(private TextRepository $texts)
    {
    }

    public function __invoke(Request $request, Response $response, Twig $view): Response
    {
        return $view->render($response, 'frontend_ng/about.html', [
            'blocks' => [
                [
                    'heading' => null,
                    'content' => $this->getTextContent('home'),
                ],
                [
                    'heading' => 'Story',
                    'content' => $this->getTextContent('story'),
                ],
                [
                    'heading' => 'Features',
                    'content' => $this->getTextContent('features'),
                ],
                [
                    'heading' => 'Spenden',
                    'content' => $this->getTextContent('spenden'),
                ],
                [
                    'heading' => 'Weitersagen',
                    'content' => $this->getTextContent('weitersagen'),
                ],
                [
                    'heading' => 'Impressum',
                    'content' => $this->getTextContent('impressum'),
                ],
            ],
        ]);
    }

    protected function getTextContent(string $keyword): ?string
    {
        $text = $this->texts->findByKeyword($keyword);
        if (null !== $text) {
            if ('' != $text->content) {
                return BBCodeConverter::toHtml($text->content);
            }
        }

        $templates = require APP_DIR . '/config/texts.php';

        return isset($templates[$keyword]) ? $templates[$keyword]->default : null;
    }
}
