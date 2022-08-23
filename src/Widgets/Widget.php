<?php

namespace App\Widgets;

use Slim\Views\Twig;

interface Widget
{
    function render(Twig $tpl): string;
}
