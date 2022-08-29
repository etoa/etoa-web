<?php

namespace App\Models\Forum;

class User
{
    public function __construct(
        public int $id,
        public string $username,
        public ?string $password = null,
        public ?string $email = null,
    ) {
    }
}
