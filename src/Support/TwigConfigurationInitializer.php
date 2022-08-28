<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Slim\Views\Twig;
use Twig\Extension\DebugExtension;
use Twig\TwigFilter;

class TwigConfigurationInitializer
{
    public static function create(bool $debug = false, bool $caching = false): Twig
    {
        $timezone = $_ENV['TIMEZONE'] ?? 'UTC';

        $twig = Twig::create(__DIR__ . '/../../templates', ['cache' => $caching ? __DIR__ . '/../../storage/cache/twig' : false, 'debug' => $debug]);

        if ($debug) {
            $twig->addExtension(new DebugExtension());
        }

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
            return Carbon::createFromTimestamp($value)->setTimezone($timezone)->isoFormat('LLL');
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('monthYear', function ($value) {
            return Carbon::createFromTimestampMs($value)->format('F Y');
        }));

        return $twig;
    }
}
