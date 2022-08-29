<?php

declare(strict_types=1);

namespace App\Controllers\Frontend\Widgets;

use Slim\Views\Twig;

interface Widget
{
    public function render(Twig $tpl): string;
}
