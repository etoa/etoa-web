<?php

namespace App\Controllers;

use App\Support\TextUtil;
use Slim\Views\Twig;

class StoryController {

    function __invoke($request, $response, $args)
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'story.html', [
            'text' => TextUtil::get("story"),
        ]);
    }
}
