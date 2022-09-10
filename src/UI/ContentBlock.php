<?php

namespace App\UI;

use Slim\Views\Twig;

interface ContentBlock
{
    public function render(Twig $twig): string;
}
