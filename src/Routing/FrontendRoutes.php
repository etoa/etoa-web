<?php

namespace App\Routing;

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
use Slim\Routing\RouteCollectorProxy;

class FrontendRoutes
{
    public function __invoke(RouteCollectorProxy $group)
    {
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
    }
}
