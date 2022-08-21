<?php

namespace App\Support;

use App\Support\Database\DB;

class ForumBridge
{
    public static function activeUsers()
    {
        $res = DB::instance('forum')->query("
            SELECT
                u.*,
                a.avatarExtension,
                a.avatarID
            FROM
                " . self::wcftable('user') . " u
            LEFT JOIN
                " . self::wcftable('user_avatar') . " a
                ON u.avatarID=a.avatarID
            WHERE
                u.userID In ((
                    SELECT DISTINCT faq_user_id
                    FROM " . dbtable('faq') . "
                    WHERE faq_user_id>0
                    UNION
                    SELECT DISTINCT comment_user_id
                    FROM " . dbtable('faq_comments') . "
                    WHERE comment_user_id>0
                ))
            GROUP BY
                u.userID
            ORDER BY
                u.username
            ;");
        $items = [];
        while ($arr = $res->fetch()) {
            $items[] = [
                'id' => $arr['userID'],
                'username' => $arr['username'],
                'avatar' => self::url('avatar', $arr['avatarID'], $arr['avatarExtension']),
            ];
        }
        return $items;
    }

    public static function userById(int $userId): ?array
    {
        $res = DB::instance('forum')->preparedQuery("
            SELECT
                u.userID,
                u.username,
                u.userTitle,
                u.registrationDate,
                a.avatarID,
                a.avatarExtension
            FROM
                " . self::wcftable('user') . " u
            LEFT JOIN
                " . self::wcftable('user_avatar') . " a
                ON u.avatarID = a.avatarID
            WHERE
                u.userID = :userId
            ;", [
                'userId' => $userId
             ]);
        if ($arr = $res->fetch()) {
            return [
                'id' => $arr['userID'],
                'username' => $arr['username'],
                'avatar' => self::url('avatar', $arr['avatarID'], $arr['avatarExtension']),
                'title' => $arr['userTitle'],
                'registration_date' => $arr['registrationDate'],
            ];
        }
        return null;
    }

    public static function userByName(string $username): ?array
    {
        $res = DB::instance('forum')->preparedQuery("
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
        if ($arr = $res->fetch()) {
            return [
                'id' => $arr['userID'],
                'username' => $arr['username'],
                'password' => $arr['password'],
                'email' => $arr['email'],
            ];
        }
        return null;
    }

    public static function authenticateUser(array $user, string $password): bool
    {
        $hash = str_starts_with($user['password'], 'Bcrypt:') ? substr($user['password'], strlen('Bcrypt:')) : $user['password'];
        return password_verify($password, $hash);
    }

    public static function groupIdsOfUser(int $userId): array
    {
        $res = DB::instance('forum')->preparedQuery("
            SELECT
                groupID
            FROM
                " . self::wcftable('user_to_group') . "
            WHERE
                userID=:userId
            ;", [
                'userId' => $userId,
            ]);
        $data = [];
        while ($arr = $res->fetch()) {
            $data[] = $arr['groupID'];
        }
        return $data;
    }

    public static function usersOfGroup(int $groupId): array
    {
        $res = DB::instance('forum')->preparedQuery("
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
        $data = [];
        while ($arr = $res->fetch()) {
            $data[] = [
                'id' => $arr['userID'],
                'username' => $arr['username'],
            ];
        }
        return $data;
    }

    public static function usersOnline(int $threshold = 300): int
    {
        $res = DB::instance('forum')->preparedQuery("
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
        $arr = $res->fetch();
        return $arr['cnt'];
    }

    public static function latestPosts($limit, $blacklist_boards = [])
    {
        $bls = '';
        if (count($blacklist_boards) > 0) {
            sort($blacklist_boards);
            $bls .= 't.boardid NOT IN (' . implode(',', $blacklist_boards) . ')';
        }

        $res = DB::instance('forum')->preparedQuery("
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
        $items = [];
        while ($arr = $res->fetch()) {
            $items[] = [
                'id' => $arr['postID'],
                'topic' => $arr['topic'],
                'time' => $arr['time'],
                'thead_id' => $arr['threadID'],
            ];
        }
        return $items;
    }

    public static function newsPosts(int $limit, int $news_board_id, int $status_board_id)
    {
        $res = DB::instance('forum')->preparedQuery("
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
        $items = [];
        while ($arr = $res->fetch()) {
            $items[] = [
                'id' => $arr['threadID'],
                'topic' => $arr['topic'],
                'time' => $arr['time'],
                'board_id' => $arr['boardID'],
                'user_id' => $arr['userid'],
                'user_name' => $arr['username'],
                'updated_at' => $arr['lastEditTime'],
                'message' => $arr['message'],
                'post_count' => $arr['post_count'],
            ];
        }
        return $items;
    }

    public static function thread($threadId)
    {
        $res = DB::instance('forum')->preparedQuery("
            SELECT *
            FROM " . self::wbbtable('post') . "
            WHERE threadid = :threadId
            ORDER BY time ASC
            LIMIT 1
            ;", [
                'threadId' => $threadId,
            ]);
        if ($arr = $res->fetch()) {
            return [
                'subject' => $arr['subject'],
                'message' => $arr['message'],
            ];
        }
        return null;
    }

    public static function url(?string $type = null, $value = null, $value2 = null): string
    {
        $baseUrl = get_config('forum_url', 'https://forum.etoa.ch/');
        if ($type == 'board') {
            return $baseUrl . '/forum/board/' . $value;
        }
        if ($type == 'thread') {
            return $baseUrl . '/forum/thread/' . $value;
        }
        if ($type == 'post') {
            return $baseUrl . '/forum/thread/'.$value2.'?postID=' . $value . '#post' . $value;
        }
        if ($type == 'user') {
            return $baseUrl . '/user/' . $value;
        }
        if ($type == 'admin') {
            return $baseUrl . '/acp/';
        }
        if ($type == 'account') {
            return $baseUrl . '/account-management/';
        }
        if ($type == 'team') {
            return $baseUrl . '/team/';
        }
        if ($type == 'avatar') {
            return $baseUrl . '/images/avatars/' . ($value > 0
                ? 'avatar-' . $value . "." . $value2
                : 'avatar-default.png');
        }
        if ($type == 'register') {
            return $baseUrl . '/register/';
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
