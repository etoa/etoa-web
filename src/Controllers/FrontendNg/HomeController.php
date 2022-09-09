<?php

namespace App\Controllers\FrontendNg;

use App\Controllers\AbstractController;
use App\Models\Round;
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
            'rounds' => array_map(fn (Round $round) => [
                'name' => $round->name,
                'url' => $round->url,
                'register_url' => $this->loginForm->getRegistrationUrl($round),
                'password_recovery_url' => $this->loginForm->getPasswordRecoveryUrl($round),
            ], $this->loginForm->getRounds()),
            'forumUrl' => ForumBridge::url(),
            'discordUrl' => $this->config->get('ts_link'),
        ]);
    }
}
