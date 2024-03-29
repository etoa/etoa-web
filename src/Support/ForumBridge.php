<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Forum\LatestPost;
use App\Models\Forum\Thread;
use App\Models\Forum\User;
use App\Support\Database\ForumDatabaseConnection;

class ForumBridge
{
    private const GUEST_GROUP_TYPE = 2;

    public function __construct(private readonly ForumDatabaseConnection $conn)
    {
    }

    public function userByName(string $username): ?User
    {
        $res = $this->conn->executeQuery('
            SELECT
                userID,
                username,
                password,
                email
            FROM
                ' . self::wcftable('user') . '
            WHERE
                username = :username
            ;', [
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
        $res = $this->conn->executeQuery('
            SELECT
                groupID
            FROM
                ' . self::wcftable('user_to_group') . '
            WHERE
                userID=:userId
            ;', [
            'userId' => $userId,
        ]);

        return $res->fetchFirstColumn();
    }

    /**
     * @return User[]
     */
    public function usersOfGroup(int $groupId): array
    {
        $res = $this->conn->executeQuery('
            SELECT
                u.userID,
                u.username
            FROM
                ' . self::wcftable('user_to_group') . ' t
            INNER JOIN
                ' . self::wcftable('user') . ' u
                ON t.userID = u.userID
                AND t.groupID = :groupId
            ;', [
            'groupId' => $groupId,
        ]);

        return array_map(fn (array $arr) => new User(
            id: $arr['userID'],
            username: $arr['username'],
        ), $res->fetchAllAssociative());
    }

    public function usersOnline(int $threshold = 300): int
    {
        $res = $this->conn->executeQuery('
            SELECT
                COUNT(*)  as cnt
            FROM
                (
                    SELECT
                        COUNT(*)
                    FROM
                        ' . self::wcftable('session') . '
                    WHERE
                        lastActivityTime > :time
                    GROUP BY
                        ipAddress,
                        userID,
                        userAgent
                ) as q
            ;', [
            'time' => time() - $threshold,
        ]);

        return $res->fetchOne();
    }

    /**
     * @return LatestPost[]
     */
    public function latestPosts(int $limit): array
    {
        $res = $this->conn->executeQuery('
            SELECT
                t.threadID,
                t.topic,
                t.lastPostTime,
                t.lastPostID,
                t.lastPoster
            FROM ' . self::wbbtable('thread') . ' t
            WHERE t.boardID IN (
                SELECT b.boardID FROM ' . self::wbbtable('board') . ' b
                WHERE boardID NOT IN (
                    SELECT otg.objectID FROM ' . self::wcftable('acl_option_to_group') . ' otg
                        INNER JOIN ' . self::wcftable('acl_option') . ' aclo ON otg.optionID = aclo.optionID
                        INNER JOIN ' . self::wcftable('object_type') . ' ot ON aclo.objectTypeID=ot.objectTypeID AND ot.objectType=\'com.woltlab.wbb.board\'
                )
                OR boardID IN (
                    SELECT otg2.objectID FROM ' . self::wcftable('acl_option_to_group') . ' otg2
                        INNER JOIN ' . self::wcftable('acl_option') . ' aclo2 ON otg2.optionID = aclo2.optionID AND aclo2.optionName = \'canReadThread\'
                        INNER JOIN ' . self::wcftable('object_type') . ' ot2 ON aclo2.objectTypeID = ot2.objectTypeID AND ot2.objectType=\'com.woltlab.wbb.board\'
                        INNER JOIN ' . self::wcftable('user_group') . ' ug2 ON ug2.groupID = otg2.groupID AND ug2.groupType = ' . self::GUEST_GROUP_TYPE . '
                    WHERE otg2.optionValue=1
                )
            )
            ORDER BY t.lastPostTime DESC
            LIMIT :limit
            ;', [
            'limit' => $limit,
        ]);

        return array_map(
            fn (array $arr) => new LatestPost(
                id: $arr['lastPostID'],
                topic: $arr['topic'],
                time: $arr['lastPostTime'],
                thread_id: $arr['threadID'],
                username: $arr['lastPoster'],
            ),
            $res->fetchAllAssociative()
        );
    }

    /**
     * @return Thread[]
     */
    public function newsPosts(int $limit, int $news_board_id, int $status_board_id): array
    {
        $res = $this->conn->executeQuery('
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
                        ' . self::wbbtable('post') . ' p2
                    WHERE
                        p2.threadID=t.threadID
                ) AS post_count
            FROM
                ' . self::wbbtable('thread') . ' t
            INNER JOIN (
                    SELECT
                        p.message,
                        p.username,
                        p.userid,
                        p.threadid,
                        p.lastEditTime
                    FROM
                    ' . self::wbbtable('post') . ' p
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
            ;', [
            'limit' => $limit,
            'news_board' => $news_board_id,
            'status_board' => $status_board_id,
        ]);

        return array_map(fn (array $arr) => new Thread(
            id: $arr['threadID'],
            topic: $arr['topic'],
            message: $arr['message'],
            time: $arr['time'],
            board_id: $arr['boardID'],
            user_id: $arr['userid'],
            user_name: $arr['username'],
            updated_at: $arr['lastEditTime'],
            last_post_time: $arr['lastPostTime'],
            post_count: $arr['post_count'],
            closed: 1 == $arr['isClosed'],
        ), $res->fetchAllAssociative());
    }

    public function thread(int $threadId): ?Thread
    {
        $res = $this->conn->executeQuery('
            SELECT
                subject,
                message
            FROM ' . self::wbbtable('post') . '
            WHERE threadid = :threadId
            ORDER BY time ASC
            LIMIT 1
            ;', [
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

    public static function url(string $type = null, string | int $value = null, string | int $value2 = null): string
    {
        $baseUrl = config('forum.url', 'https://forum.etoa.ch/');
        if ('board' == $type) {
            return $baseUrl . 'forum/board/' . $value;
        }
        if ('thread' == $type) {
            return $baseUrl . 'forum/thread/' . $value;
        }
        if ('post' == $type) {
            return $baseUrl . 'forum/thread/' . $value2 . '?postID=' . $value . '#post' . $value;
        }
        if ('user' == $type) {
            return $baseUrl . 'user/' . $value;
        }
        if ('admin' == $type) {
            return $baseUrl . 'acp/';
        }
        if ('account' == $type) {
            return $baseUrl . 'account-management/';
        }
        if ('team' == $type) {
            return $baseUrl . 'team/';
        }
        if ('register' == $type) {
            return $baseUrl . 'register/';
        }

        return $baseUrl;
    }

    private static function wcftable(string $name): string
    {
        return 'wcf1_' . $name;
    }

    private static function wbbtable(string $name): string
    {
        return 'wbb1_' . $name;
    }
}
