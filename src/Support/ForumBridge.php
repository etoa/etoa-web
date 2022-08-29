<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Forum\LatestPost;
use App\Models\Forum\Thread;
use App\Models\Forum\User;
use App\Support\Database\ForumDatabaseConnection;

class ForumBridge
{
    public function __construct(private ForumDatabaseConnection $conn)
    {
    }

    /**
     * @return User[]
     */
    public function userByName(string $username): ?User
    {
        $res = $this->conn->executeQuery("
            SELECT
                userID,
                username,
                password,
                email
            FROM
                " . self::wcftable('user') . "
            WHERE
                username = :username
            ;", [
            'username' => $username,
        ]);
        if ($arr = $res->fetchAssociative()) {
            return new User(
                id: $arr['userID'],
                username: $arr['username'],
                password: $arr['password'],
                email: $arr['email'],
            );
        }

        return null;
    }

    /**
     * @return int[]
     */
    public function groupIdsOfUser(int $userId): array
    {
        $res = $this->conn->executeQuery("
            SELECT
                groupID
            FROM
                " . self::wcftable('user_to_group') . "
            WHERE
                userID=:userId
            ;", [
            'userId' => $userId,
        ]);

        return (array) $res->fetchFirstColumn();
    }

    /**
     * @return User[]
     */
    public function usersOfGroup(int $groupId): array
    {
        $res = $this->conn->executeQuery("
            SELECT
                u.userID,
                u.username
            FROM
                " . self::wcftable('user_to_group') . " t
            INNER JOIN
                " . self::wcftable('user') . " u
                ON t.userID = u.userID
                AND t.groupID = :groupId
            ;", [
            'groupId' => $groupId,
        ]);

        return array_map(fn (array $arr) => new User(
            id: $arr['userID'],
            username: $arr['username'],
        ), (array) $res->fetchAllAssociative());
    }

    public function usersOnline(int $threshold = 300): int
    {
        $res = $this->conn->executeQuery("
            SELECT
                COUNT(*)  as cnt
            FROM
                (
                    SELECT
                        COUNT(*)
                    FROM
                        " . self::wcftable('session') . "
                    WHERE
                        lastActivityTime > :time
                    GROUP BY
                        ipAddress,
                        userID,
                        userAgent
                ) as q
            ;", [
            'time' => time() - $threshold,
        ]);
        return $res->fetchOne();
    }

    /**
     * @return LatestPost[]
     */
    public function latestPosts(int $limit, array $blacklist_boards = []): array
    {
        $bls = '';
        if (count($blacklist_boards) > 0) {
            sort($blacklist_boards);
            $bls .= 't.boardid NOT IN (' . implode(',', $blacklist_boards) . ')';
        }

        $res = $this->conn->executeQuery("
            SELECT
                t.topic,
                p.postID,
                t.threadID,
                p.username,
                p.time
            FROM
                " . self::wbbtable('thread') . " t
            INNER JOIN
                " . self::wbbtable('post') . " p
                ON p.postID = (
                    SELECT p2.`postID`
                    FROM `" . self::wbbtable('post') . "` p2
                    WHERE p2.`threadID` = t.`threadID`
                    ORDER BY p2.`time` DESC
                    LIMIT 1
                )
            WHERE " . $bls . "
            ORDER BY p.time DESC
            LIMIT :limit
            ;", [
            'limit' => $limit
        ]);
        return array_map(
            fn (array $arr) => new LatestPost(
                id: $arr['postID'],
                topic: $arr['topic'],
                time: $arr['time'],
                thread_id: $arr['threadID'],
            ),
            (array) $res->fetchAllAssociative()
        );
    }

    public function newsPosts(int $limit, int $news_board_id, int $status_board_id): array
    {
        $res = $this->conn->executeQuery("
            SELECT
                t.topic,
                t.time,
                t.threadID,
                t.isClosed,
                t.boardID,
                t.lastPostTime,
                pp.message,
                pp.username,
                pp.userid,
                pp.threadid,
                pp.lastEditTime,
                (
                    SELECT
                        COUNT(p2.threadID)
                    FROM
                        " . self::wbbtable('post') . " p2
                    WHERE
                        p2.threadID=t.threadID
                ) AS post_count
            FROM
                " . self::wbbtable('thread') . " t
            INNER JOIN (
                    SELECT
                        p.message,
                        p.username,
                        p.userid,
                        p.threadid,
                        p.lastEditTime
                    FROM
                    " . self::wbbtable('post') . " p
                    ORDER BY p.time
                ) pp
                ON pp.threadID=t.threadID
                AND (
                    t.boardID = :news_board
                    OR
                    (
                        t.boardID = :status_board
                        AND t.isClosed = 0
                    )
                )
            GROUP BY
                t.threadID
            ORDER BY
                t.time DESC
            LIMIT :limit
            ;", [
            'limit' => $limit,
            'news_board' => $news_board_id,
            'status_board' => $status_board_id,
        ]);
        return array_map(fn (array $arr) => [
            'id' => $arr['threadID'],
            'topic' => $arr['topic'],
            'time' => $arr['time'],
            'board_id' => $arr['boardID'],
            'user_id' => $arr['userid'],
            'user_name' => $arr['username'],
            'updated_at' => $arr['lastEditTime'],
            'message' => $arr['message'],
            'post_count' => $arr['post_count'],
            'last_post_time' => $arr['lastPostTime'],
        ], (array) $res->fetchAllAssociative());
    }

    public function thread(int $threadId): ?Thread
    {
        $res = $this->conn->executeQuery("
            SELECT
                subject,
                message
            FROM " . self::wbbtable('post') . "
            WHERE threadid = :threadId
            ORDER BY time ASC
            LIMIT 1
            ;", [
            'threadId' => $threadId,
        ]);
        if ($arr = $res->fetchAssociative()) {
            return new Thread(
                id: $threadId,
                topic: $arr['subject'],
                message: $arr['message'],
            );
        }
        return null;
    }

    public static function authenticateUser(User $user, string $password): bool
    {
        $hash = str_starts_with($user->password, 'Bcrypt:') ? substr($user->password, strlen('Bcrypt:')) : $user->password;
        return password_verify($password, $hash);
    }

    public static function url(?string $type = null, $value = null, $value2 = null): string
    {
        $baseUrl = config('forum.url');
        if ($type == 'board') {
            return $baseUrl . 'forum/board/' . $value;
        }
        if ($type == 'thread') {
            return $baseUrl . 'forum/thread/' . $value;
        }
        if ($type == 'post') {
            return $baseUrl . 'forum/thread/' . $value2 . '?postID=' . $value . '#post' . $value;
        }
        if ($type == 'user') {
            return $baseUrl . 'user/' . $value;
        }
        if ($type == 'admin') {
            return $baseUrl . 'acp/';
        }
        if ($type == 'account') {
            return $baseUrl . 'account-management/';
        }
        if ($type == 'team') {
            return $baseUrl . 'team/';
        }
        if ($type == 'register') {
            return $baseUrl . 'register/';
        }
        return $baseUrl;
    }

    private static function wcftable($name)
    {
        return 'wcf1_' . $name;
    }

    private static function wbbtable($name)
    {
        return 'wbb1_' . $name;
    }
}
