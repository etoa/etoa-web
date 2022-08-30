<?php

namespace App\Models\Forum;

class LatestPost
{
    public function __construct(
        public int $id,
        public string $topic,
        public int $time,
        public int $thread_id,
    ) {
    }
}
