<?php

namespace App\Support;

use App\Repository\RoundRepository;

class GameLoginFormService
{
    public function __construct(
        private RoundRepository $rounds,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function createLoginFormData(): array
    {
        $time = time();
        $logintoken = sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $time) . dechex($time);

        return [
            'logintoken' => $logintoken,
            'nickField' => sha1('nick' . $logintoken . $time),
            'passwordField' => sha1('password' . $logintoken . $time),
            'rnd' => mt_rand(10000, 99999),
        ];
    }

    /**
     * @return Round[]
     */
    public function getRounds(): array
    {
        return $this->rounds->active();
    }
}
