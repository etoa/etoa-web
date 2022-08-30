<?php

declare(strict_types=1);

namespace App\Authentication;

use App\Support\ForumBridge;

class ForumAuthenticator
{
    public function __construct(private ForumBridge $forum)
    {
    }

    /**
     * @param string[] $arguments
     */
    public function __invoke(array $arguments): bool
    {
        if (!isset($arguments['user'])) {
            return false;
        }

        $user = $this->forum->userByName($arguments['user']);
        if ($user === null || !ForumBridge::authenticateUser($user, $arguments['password'])) {
            return false;
        }

        $userGroupIds = $this->forum->groupIdsOfUser($user->id);
        $allowedGroup = config('auth.admin.usergroup');
        return in_array($allowedGroup, $userGroupIds);
    }
}
