<?php

declare(strict_types=1);

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

/** @var \Slim\App $app */

$app->get('/story', StoryController::class)->setName('story');
$app->get('/disclaimer', DisclaimerController::class)->setName('disclaimer');
$app->get('/privacy', PrivacyController::class)->setName('privacy');
$app->redirect('/impressum', 'imprint');
$app->get('/imprint', ImprintController::class)->setName('imprint');
$app->get('/history', HistoryController::class)->setName('history');
$app->get('/features', FeaturesController::class)->setName('features');
$app->get('/banner', BannerController::class)->setName('banner');
$app->redirect('/regeln', 'rules');
$app->get('/rules', RulesController::class)->setName('rules');
$app->get('/news', NewsController::class)->setName('news');
$app->get('/logout', LogoutController::class)->setName('logout');
$app->get('/err', ErrorController::class)->setName('error');
$app->get('/screenshots', ScreenshotsController::class)->setName('screenshots');
$app->redirect('/spenden', 'donate');
$app->get('/donate', DonateController::class)->setName('donate');
$app->get('/register', RegisterController::class)->setName('register');
$app->get('/pwrequest', RequestPasswordController::class)->setName('pwrequest');
