<?php

namespace App\Support;

use StringUtil;

class ForumBridge
{
    public static function activeUsers()
    {
        $res = dbquery("
            SELECT
                u.*,
                a.avatarExtension,
                a.avatarID
            FROM
                " . self::wcftable('user') . " u
            LEFT JOIN
                " . self::wcftable('avatar') . " a
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
        while ($arr = mysql_Fetch_assoc($res)) {
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
        $ures = dbquery("
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
                " . self::wcftable('avatar') . " a
                ON u.avatarID = a.avatarID
            WHERE
                u.userID = '" . $userId . "'
        ;");
        if (mysql_num_rows($ures) > 0) {
            $arr = mysql_Fetch_assoc($ures);
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
        $res = dbquery("
            SELECT
                userID,
                username,
                salt,
                email
            FROM
                " . self::wcftable('user') . "
            WHERE
                username='" . $username . "'
        ;");
        if (mysql_num_rows($res) > 0) {
            $arr = mysql_fetch_array($res);
            return [
                'id' => $arr['userID'],
                'username' => $arr['username'],
                'salt' => $arr['salt'],
                'email' => $arr['email'],
            ];
        }
        return null;
    }

    public static function authenticateUser(array $user, string $password): bool
    {
        require_once(__DIR__ . "/../../forum/wcf/lib/util/StringUtil.class.php");

        $res = dbquery("
            SELECT
                userID
            FROM
                " . self::wcftable('user') . "
            WHERE
                password='" . StringUtil::getDoubleSaltedHash($password, $user['salt']) . "'
            AND
                userID='" . $user['id'] . "'
        ;");
        return mysql_num_rows($res) > 0;
    }

    public static function groupIdsOfUser(int $userId): array
    {
        $res = dbquery("
            SELECT
                groupID
            FROM
                " . self::wcftable('user_to_groups') . "
            WHERE
                userID='" . $userId . "'
            ;");
        $data = [];
        while ($arr = mysql_fetch_row($res)) {
            $data[] = $arr[0];
        }
        return $data;
    }

    public static function usersOfGroup(int $groupId): array
    {
        $res = dbquery("
            SELECT
                u.userID,
                u.username
            FROM
                " . self::wcftable('user_to_groups') . " t
            INNER JOIN
                " . self::wcftable('user') . " u
                ON t.userID=u.userID
                AND t.groupID = " . $groupId . ";
        ;");
        $data = [];
        while ($arr = mysql_fetch_assoc($res)) {
            $data[] = [
                'id' => $arr['userID'],
                'username' => $arr['username'],
            ];
        }
        return $data;
    }

    public static function usersOnline(int $threshold = 1000): int
    {
        $res = dbquery("
            SELECT
                COUNT(sessionID)
            FROM
                " . self::wcftable('session') . "
            WHERE
                lastActivityTime >" . (time() - $threshold) . "
            ;");
        $arr = mysql_fetch_row($res);
        return $arr[0];
    }

    public static function latestPosts($limit, $blacklist_boards = [])
    {
        $bls = '';
        if (count($blacklist_boards) > 0) {
            sort($blacklist_boards);
            $bls .= 't.boardid NOT IN (' . implode(',', $blacklist_boards) . ')';
        }

        $res = dbquery("
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
            LIMIT " . $limit . ";");
        $items = [];
        while ($arr = mysql_fetch_assoc($res)) {
            $items[] = [
                'id' => $arr['postID'],
                'topic' => $arr['topic'],
                'time' => $arr['time'],
            ];
        }
        return $items;
    }

    public static function newsPosts(int $limit, int $news_board_id, int $status_board_id)
    {
        $res = dbquery("
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
                    t.boardID=" . $news_board_id . "
                    OR
                    (
                        t.boardID=" . $status_board_id . "
                        AND t.isClosed=0
                    )
                )
            GROUP BY
                t.threadID
            ORDER BY
                t.time DESC
            LIMIT " . $limit . "
            ;");
        $items = [];
        while ($arr = mysql_fetch_array($res)) {
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
        $res = dbquery("
            SELECT *
            FROM " . self::wbbtable('post') . "
            WHERE threadid = " . $threadId . "
            ORDER BY time ASC
            LIMIT 1;");
        if (mysql_num_rows($res) > 0) {
            $arr = mysql_fetch_array($res);
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
            return $baseUrl . '/index.php?page=Board&amp;boardID=' . $value;
        }
        if ($type == 'thread') {
            return $baseUrl . '/index.php?page=Thread&amp;threadID=' . $value;
        }
        if ($type == 'post') {
            return $baseUrl . '/index.php?page=Thread&amp;postID=' . $value . '#post' . $value;
        }
        if ($type == 'addpost') {
            return $baseUrl . '/index.php?form=PostAdd&amp;threadID=' . $value;
        }
        if ($type == 'user') {
            return $baseUrl . '/index.php?page=User&amp;userID=' . $value;
        }
        if ($type == 'admin') {
            return $baseUrl . '/acp';
        }
        if ($type == 'account') {
            return $baseUrl . '/index.php?form=AccountManagement';
        }
        if ($type == 'team') {
            return $baseUrl . '/index.php?page=Team';
        }
        if ($type == 'avatar') {
            return $baseUrl . '/wcf/images/avatars/' . ($value > 0
                ? 'avatar-' . $value . "." . $value2
                : 'avatar-default.png');
        }
        if ($type == 'register') {
            return $baseUrl . '/index.php?page=Register';
        }
        return $baseUrl;
    }

    private static function wcftable($name)
    {
        return 'wcf1_' . $name;
    }

    private static function wbbtable($name)
    {
        return 'wbb1_1_' . $name;
    }
}
