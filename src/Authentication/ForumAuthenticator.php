<?php

declare(strict_types=1);

namespace App\Authentication;

use App\Support\ForumBridge;
use Monolog\Logger;

class ForumAuthenticator
{
    public function __construct(private ForumBridge $forum, private Logger $logger, protected \SlimSession\Helper $session)
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
        if (null === $user || !ForumBridge::authenticateUser($user, $arguments['password'])) {
            $this->logger->warning('Invalid user login.', [
                'user.name' => $user?->username,
                'auth.user' => $arguments['user'],
            ]);

            return false;
        }

        $userGroupIds = $this->forum->groupIdsOfUser($user->id);
        $allowedGroup = config('auth.admin.usergroup');

        if (!in_array($allowedGroup, $userGroupIds)) {
            $this->logger->warning('User tried to authenticate but is not in allowed groupd.', [
                'user.name' => $user->username,
                'user.groups' => $userGroupIds,
                'admin.group' => $allowedGroup,
            ]);

            return false;
        }

        if (!$this->session->exists('user') || $this->session->get('user')->username != $user->username) {
            $this->session->set('user', $user);
            $this->logger->info('Admin user logged in.', [
                'user.name' => $user->username,
                'user.groups' => $userGroupIds,
            ]);
        }

        return true;
    }
}
