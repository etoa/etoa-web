<?php

declare(strict_types=1);

use App\Controllers\Frontend\BannerController;
use App\Controllers\Frontend\DisclaimerController;
use App\Controllers\Frontend\FeaturesController;
use App\Controllers\Frontend\HistoryController;
use App\Controllers\Frontend\ImprintController;
use App\Controllers\Frontend\NewsController;
use App\Controllers\Frontend\PrivacyController;
use App\Controllers\Frontend\RulesController;
use App\Controllers\Frontend\StoryController;

/** @var \Slim\App $app */

$app->get('/story', StoryController::class)->setName('story');
$app->get('/disclaimer', DisclaimerController::class)->setName('disclaimer');
$app->get('/privacy', PrivacyController::class)->setName('privacy');
$app->get('/impressum', ImprintController::class)->setName('impressum');
$app->get('/history', HistoryController::class)->setName('history');
$app->get('/features', FeaturesController::class)->setName('features');
$app->get('/banner', BannerController::class)->setName('banner');
$app->get('/regeln', RulesController::class)->setName('regeln');
$app->get('/news', NewsController::class)->setName('news');
