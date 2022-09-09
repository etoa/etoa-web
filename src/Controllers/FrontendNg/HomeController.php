<?php

namespace App\Controllers\FrontendNg;

use App\Controllers\AbstractController;
use App\Repository\ConfigSettingRepository;
use App\Support\ForumBridge;
use App\Support\GameLoginFormService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeController extends AbstractController
{
    public function __construct(
        private GameLoginFormService $loginForm,
        protected ConfigSettingRepository $config,
    ) {
    }

    public function __invoke(Request $request, Response $response, Twig $view): Response
    {
        return $view->render($response, 'frontend_ng/home.html', [
            'loginform' => $this->loginForm->createLoginFormData(),
            'rounds' => $this->loginForm->getRounds(),
            'forumUrl' => ForumBridge::url(),
            'discordUrl' => $this->config->get('ts_link'),
        ]);
    }
}
