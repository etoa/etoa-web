<?php

declare(strict_types=1);

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use CodeInc\HumanReadableFileSize\HumanReadableFileSize;
use Slim\Views\Twig;
use Twig\Extension\DebugExtension;
use Twig\TwigFilter;

class TwigConfigurationInitializer
{
    public static function create(bool $debug = false, bool $caching = false): Twig
    {
        $timezone = config('app.timezone', 'UTC');

        $twig = Twig::create(APP_DIR . '/templates', [
            'cache' => $caching ? CACHE_DIR . '/twig' : false,
            'debug' => $debug,
        ]);

        if ($debug) {
            $twig->addExtension(new DebugExtension());
        }

        $twig->getEnvironment()->addFilter(new \Twig\TwigFilter('humanSize', function ($value) {
            return null !== $value ? HumanReadableFileSize::getHumanSize($value, 1) : null;
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('humanInterval', function ($value) {
            return CarbonInterval::seconds($value)->cascade()->forHumans();
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('diffForHumans', function ($value) {
            return Carbon::createFromDate($value)->diffForHumans();
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('diffForHumansFromTimestamp', function ($value) {
            return Carbon::createFromTimestamp($value)->diffForHumans();
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('localDateFormat', function ($value) use ($timezone) {
            return Carbon::createFromDate($value)->setTimezone($timezone)->isoFormat('LLL');
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('localDateFormatFromTimestamp', function ($value) use ($timezone) {
            return Carbon::createFromTimestamp($value)->setTimezone($timezone)->isoFormat('LL');
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('localDateTimeFormatFromTimestamp', function ($value) use ($timezone) {
            return Carbon::createFromTimestamp($value)->setTimezone($timezone)->isoFormat('LLL');
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('dateFromTimestamp', function ($value) use ($timezone) {
            return $value > 0 ? Carbon::createFromTimestamp($value)->setTimezone($timezone)->toDateString() : '';
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('bbcode', function ($value) {
            return BBCodeConverter::toHtml($value);
        }, ['is_safe' => ['html']]));

        return $twig;
    }
}
