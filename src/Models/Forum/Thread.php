<?php

namespace App\Models\Forum;

class Thread
{
    public function __construct(
        public int $id,
        public string $topic,
        public string $message,
        public ?int $time = null,
        public ?int $board_id = null,
        public ?int $user_id = null,
        public ?string $user_name = null,
        public ?int $updated_at = null,
        public ?int $last_post_time = null,
        public int $post_count = 1,
        public bool $closed = false,
    ) {
    }
}
