<?php

namespace App\Widgets;

use App\TemplateEngine;

interface Widget
{
    function render(TemplateEngine $tpl): string;
}
