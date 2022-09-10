<?php

declare(strict_types=1);

namespace App\Routing;

use App\Controllers\Frontend\AboutController;
use App\Controllers\Frontend\ErrorController;
use App\Controllers\Frontend\LegalController;
use App\Controllers\Frontend\LogoutController;
use App\Controllers\Frontend\NewsController;
use App\Controllers\Frontend\RegisterController;
use App\Controllers\Frontend\RequestPasswordController;
use App\Controllers\Frontend\RulesController;
use App\Controllers\Frontend\ScreenshotsController;
use App\Controllers\Frontend\StartPageController;
use App\Controllers\Frontend\SupportUsController;
use Slim\Routing\RouteCollectorProxy;

class FrontendRoutes
{
    public function __invoke(RouteCollectorProxy $group): void
    {
        $group->get('', StartPageController::class)
            ->setName('home');

        $group->get('/news', NewsController::class)
            ->setName('news');

        $group->redirect('/features', 'about');
        $group->redirect('/history', 'about');
        $group->redirect('/story', 'about');
        $group->get('/about', AboutController::class)
            ->setName('about');

        $group->get('/screenshots', ScreenshotsController::class)
            ->setName('screenshots');

        $group->redirect('/disclaimer', 'legal');
        $group->redirect('/privacy', 'legal');
        $group->redirect('/impressum', 'legal');
        $group->get('/legal', LegalController::class)
            ->setName('legal');

        $group->redirect('/spenden', 'donate');
        $group->redirect('/banner', 'donate');
        $group->get('/donate', SupportUsController::class)
            ->setName('donate');

        $group->redirect('/regeln', 'rules');
        $group->get('/rules', RulesController::class)
            ->setName('rules');

        $group->get('/register', RegisterController::class)
            ->setName('register');
        $group->get('/pwrequest', RequestPasswordController::class)
            ->setName('pwrequest');

        $group->get('/logout', LogoutController::class)
            ->setName('logout');
        $group->get('/err', ErrorController::class)
            ->setName('error');
    }
}
