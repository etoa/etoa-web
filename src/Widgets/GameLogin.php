<?php

namespace App\Widgets;

use App\Models\Round;
use App\TemplateEngine;

class GameLogin implements Widget
{
    public function render(TemplateEngine $tpl): string
    {
        $t = time();
        $logintoken = sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $t) . dechex($t);
        $tpl->assign('loginform', array(
            'logintoken' => $logintoken,
            'nickField' => sha1("nick" . $logintoken . $t),
            'passwordField' => sha1("password" . $logintoken . $t),
            'rnd' => mt_rand(10000, 99999)
        ));
        $tpl->assign('rounds', Round::active());
        $tpl->assign('selectedRound', isset($_COOKIE['round']) ? $_COOKIE['round'] : '');
        return $tpl->fetch('widgets/game-login.html');
    }
}
