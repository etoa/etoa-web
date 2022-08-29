<?php

namespace App\Models\Forum;

class Thread
{
    public function __construct(
        public int $id,
        public string $topic,
        public string $message,
    ) {
    }
}
