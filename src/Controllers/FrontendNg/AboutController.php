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
                'home' => $this->getTextContent('home'),
                'story' => $this->getTextContent('story'),
                'features' => $this->getTextContent('features'),
                'spenden' => $this->getTextContent('spenden'),
                'weitersagen' => $this->getTextContent('weitersagen'),
                'impressum' => $this->getTextContent('impressum'),
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
