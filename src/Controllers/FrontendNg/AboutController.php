<?php

namespace App\Controllers\FrontendNg;

use App\Controllers\AbstractController;
use App\Repository\TextRepository;
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
                    'content' => $this->texts->getContent('home'),
                ],
                [
                    'heading' => 'Story',
                    'content' => $this->texts->getContent('story'),
                ],
                [
                    'heading' => 'Features',
                    'content' => $this->texts->getContent('features'),
                ],
                [
                    'heading' => 'Spenden',
                    'content' => $this->texts->getContent('spenden'),
                ],
                [
                    'heading' => 'Weitersagen',
                    'content' => $this->texts->getContent('weitersagen'),
                ],
                [
                    'heading' => 'Impressum',
                    'content' => $this->texts->getContent('impressum'),
                ],
            ],
        ]);
    }
}
