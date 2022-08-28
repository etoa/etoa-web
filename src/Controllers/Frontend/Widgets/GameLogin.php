<?php

declare(strict_types=1);

namespace App\Controllers\Frontend\Widgets;

use App\Service\RoundService;
use Slim\Views\Twig;

class GameLogin implements Widget
{
    function __construct(private RoundService $rounds)
    {
    }

    public function render(Twig $view): string
    {
        $t = time();
        $logintoken = sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $t) . dechex($t);
        return $view->fetch('frontend/widgets/game-login.html', [
            'loginform' => [
                'logintoken' => $logintoken,
                'nickField' => sha1("nick" . $logintoken . $t),
                'passwordField' => sha1("password" . $logintoken . $t),
                'rnd' => mt_rand(10000, 99999)
            ],
            'rounds' => $this->rounds->active(),
            'selectedRound' => isset($_COOKIE['round']) ? $_COOKIE['round'] : '',
        ]);
    }
}
