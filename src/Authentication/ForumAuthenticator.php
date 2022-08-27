<?php

namespace App\Authentication;

use App\Support\ForumBridge;

class ForumAuthenticator
{
    public function __invoke(array $arguments): bool
    {
        if (!isset($arguments['user'])) {
            return false;
        }

        $user = ForumBridge::userByName($arguments['user']);
        if ($user === null || !ForumBridge::authenticateUser($user, $arguments['password'])) {
            return false;
        }

        $userGroupIds = ForumBridge::groupIdsOfUser($user['id']);
        $allowedGroup = get_config('loginadmin_group', 4);
        return in_array($allowedGroup, $userGroupIds);
    }
}
