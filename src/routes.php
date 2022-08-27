<?php

declare(strict_types=1);

use App\Authentication\ForumAuthenticator;
use App\Controllers\Frontend\BannerController;
use App\Controllers\Frontend\DisclaimerController;
use App\Controllers\Frontend\DonateController;
use App\Controllers\Frontend\ErrorController;
use App\Controllers\Frontend\FeaturesController;
use App\Controllers\Frontend\HistoryController;
use App\Controllers\Frontend\ImprintController;
use App\Controllers\Frontend\LogoutController;
use App\Controllers\Frontend\NewsController;
use App\Controllers\Frontend\PrivacyController;
use App\Controllers\Frontend\RegisterController;
use App\Controllers\Frontend\RequestPasswordController;
use App\Controllers\Frontend\RulesController;
use App\Controllers\Frontend\ScreenshotsController;
use App\Controllers\Frontend\StoryController;
use App\Controllers\PageNotFoundController;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\Twig;
use Tuupola\Middleware\HttpBasicAuthentication;

/** @var \Slim\App $app */

if (isMaintenanceModeActive()) {
    $app->any('/', fn (Response $response, Twig $view) => $view->render($response, 'maintenance.html'));
    $app->any('/{any}', fn (Response $response, Twig $view) => $view->render($response, 'maintenance.html'));
} else {
    $app->group('', function (RouteCollectorProxy $group) {
        $group->redirect('/', 'news')
            ->setName('home');
        $group->get('/story', StoryController::class)
            ->setName('story');
        $group->get('/disclaimer', DisclaimerController::class)
            ->setName('disclaimer');
        $group->get('/privacy', PrivacyController::class)
            ->setName('privacy');
        $group->redirect('/impressum', 'imprint');
        $group->get('/imprint', ImprintController::class)
            ->setName('imprint');
        $group->get('/history', HistoryController::class)
            ->setName('history');
        $group->get('/features', FeaturesController::class)
            ->setName('features');
        $group->get('/banner', BannerController::class)
            ->setName('banner');
        $group->redirect('/regeln', 'rules');
        $group->get('/rules', RulesController::class)
            ->setName('rules');
        $group->get('/news', NewsController::class)
            ->setName('news');
        $group->get('/logout', LogoutController::class)
            ->setName('logout');
        $group->get('/err', ErrorController::class)
            ->setName('error');
        $group->get('/screenshots', ScreenshotsController::class)
            ->setName('screenshots');
        $group->redirect('/spenden', 'donate');
        $group->get('/donate', DonateController::class)
            ->setName('donate');
        $group->get('/register', RegisterController::class)->setName('register');
        $group->get('/pwrequest', RequestPasswordController::class)
            ->setName('pwrequest');
    });

    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->get('', function (Response $response) {
            $response->getBody()->write('test');
            return $response;
        })
            ->setName('admin');
    })->add(new HttpBasicAuthentication([
        "realm" => "EtoA Login Administration",
        "authenticator" => new ForumAuthenticator,
        "before" => function ($request, $arguments) {
            return $request
                ->withAttribute("user", $arguments["user"])
                ->withAttribute("password", $arguments["password"]);
        },
        'secure' => false,
    ]));

    $app->any('/{path:.*}', PageNotFoundController::class);
}
