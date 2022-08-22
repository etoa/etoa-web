<?php

namespace App\Support;

use App\Support\Database\DB;
use PDO;

class ForumBridge
{
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

    public static function migrateHelp($boardId)
    {
        $ures = DB::instance('forum')->preparedQuery("
            SELECT userID, username
            FROM " . self::wcftable('user') . "
            order by userID
            ;", []);
        $users = [];
        foreach ($ures->fetchAll() as $arr) {
            $users[$arr['userID']] = $arr['username'];
        }

        $earliestTime = DB::instance('default')->query("
            SELECT faq_user_time FROM " . dbtable('faq') . "
            where faq_user_time > 0
            order by faq_user_time ASC
            limit 1;")
            ->fetchColumn();

        $res = DB::instance('default')->preparedQuery("
            SELECT *
            FROM " . dbtable('faq') . "
            LIMIT 50
            ;", []);
        foreach ($res->fetchAll() as $faq) {
            echo $faq['faq_question']."\n";

            $cres = DB::instance('default')->preparedQuery("
            SELECT *
            FROM " . dbtable('faq_comments') . "
            WHERE comment_faq_id = :faq_id
            ;", [
                'faq_id' => $faq['faq_id'],
            ]);
            $comments = $cres->fetchAll();

            $tres = DB::instance('default')->preparedQuery("
            SELECT t.name
            FROM " . dbtable('help_tag') . " as t
            INNER JOIN " . dbtable('help_tag_rel') . " r ON t.id = r.tag_id AND domain='faq'
            AND r.item_id = :faq_id
            ;", [
                'faq_id' => $faq['faq_id'],
            ]);
            $tags = $tres->fetchAll(PDO::FETCH_COLUMN);

            echo "Insert thread ".$faq['faq_question']."\n";
            DB::instance('forum')->preparedQuery("
                INSERT INTO " . self::wbbtable('thread') . "
                (boardID, topic, time, userID, username, views, replies)
                VALUES (:boardID, :topic, :time, :userID, :username, :views, :replies)
                ;", [
                    'boardID' => $boardId,
                    'topic' => $faq['faq_question'],
                    'time' => $faq['faq_user_time'] > 0 ? $faq['faq_user_time'] : $earliestTime,
                    'userID' => $faq['faq_user_id'] > 0 && isset($users[$faq['faq_user_id']]) ? $faq['faq_user_id'] : null,
                    'username' => $faq['faq_user_id'] > 0 && isset($users[$faq['faq_user_id']]) ? $users[$faq['faq_user_id']] : '',
                    'views' => $faq['faq_views'],
                    'replies' => count($comments),
                ]);
            $threadId = DB::instance('forum')->lastInsertId();

            foreach ($tags as $tag) {
                $tres = DB::instance('forum')->preparedQuery("
                    SELECT tagID FROM " . self::wcftable('tag') . "
                    WHERE name = :name
                    ;", [
                        'name' => $tag,
                    ]);
                $tagId = $tres->fetchColumn();
                if (!$tagId) {
                    echo "Insert tag $tag\n";
                    DB::instance('forum')->preparedQuery("
                        INSERT INTO " . self::wcftable('tag') . "
                        (languageID, name)
                        VALUES (1, :name)
                        ;", [
                            'name' => $tag,
                        ]);
                    $tagId = DB::instance('forum')->lastInsertId();
                }

                echo "Insert tag relation for $tagId\n";
                DB::instance('forum')->preparedQuery("
                    INSERT INTO " . self::wcftable('tag_to_object') . "
                    (objectID, tagID, objectTypeID, languageID)
                    VALUES (:objectID, :tagID, 349, 1)
                    ;", [
                        'objectID' => $threadId,
                        'tagID' => $tagId,
                    ]);
            }

            echo "Insert first post\n";
            DB::instance('forum')->preparedQuery("
                INSERT INTO " . self::wbbtable('post') . "
                (threadID, userID, username, message, time, ipAddress)
                VALUES (:threadID, :userID, :username, :message, :time, :ipAddress)
                ;", [
                    'threadID' => $threadId,
                    'userID' => $faq['faq_user_id'] > 0 && isset($users[$faq['faq_user_id']]) ? $faq['faq_user_id'] : null,
                    'username' => $faq['faq_user_id'] > 0 && isset($users[$faq['faq_user_id']]) ? $users[$faq['faq_user_id']] : $faq['faq_user_nick'],
                    'message' => $faq['faq_description'],
                    'time' => $faq['faq_user_time'] > 0 ? $faq['faq_user_time'] : $earliestTime,
                    'ipAddress' => $faq['faq_user_ip'],
                ]);
            $firstPostID = DB::instance('forum')->lastInsertId();
            $lastPostID = null;
            $lastPostTime  = 0;
            $lastPosterID = null;
            $lastPoster = '';
            $bestAnswerPostID = null;

            foreach ($comments as $comment) {
                $lastPosterID = $comment['comment_user_id'] > 0 && isset($users[$comment['comment_user_id']]) ? $comment['comment_user_id'] : null;
                $lastPoster = $comment['comment_user_id'] > 0 && isset($users[$comment['comment_user_id']]) ? $users[$comment['comment_user_id']] : $comment['comment_nick'];
                $lastPostTime = $comment['comment_time'] > 0 ? $comment['comment_time'] : $earliestTime;
                echo "Insert post ".substr($comment['comment_text'], 20)."\n";
                DB::instance('forum')->preparedQuery("
                    INSERT INTO " . self::wbbtable('post') . "
                    (threadID, userID, username, message, time, enableHtml, ipAddress)
                    VALUES (:threadID, :userID, :username, :message, :time, 1, :ipAddress)
                    ;", [
                        'threadID' => $threadId,
                        'userID' => $lastPosterID,
                        'username' => $lastPoster,
                        'message' => StringUtil::text2html($comment['comment_text']),
                        'time' => $lastPostTime,
                        'ipAddress' => $comment['comment_ip'],
                    ]);
                $lastPostID = DB::instance('forum')->lastInsertId();
                if ($comment['comment_correct']) {
                    $bestAnswerPostID = $lastPostID;
                }
            }

            DB::instance('forum')->preparedQuery("
                UPDATE " . self::wbbtable('thread') . "
                SET firstPostID = :firstPostID,
                    lastPostID = :lastPostID,
                    lastPostTime = :lastPostTime,
                    lastPosterID = :lastPosterID,
                    lastPoster = :lastPoster,
                    bestAnswerPostID = :bestAnswerPostID
                WHERE threadID = :threadID
                ;", [
                    'firstPostID' => $firstPostID,
                    'lastPostID' => $lastPostID,
                    'lastPostTime' => $lastPostTime,
                    'lastPosterID' => $lastPosterID,
                    'lastPoster' => $lastPoster,
                    'threadID' => $threadId,
                    'bestAnswerPostID' => $bestAnswerPostID,
            ]);

            $res = DB::instance('default')->preparedQuery("
            DELETE FROM " . dbtable('faq') . " WHERE faq_id=:faq_id
            ;", [
                'faq_id' => $faq['faq_id'],
            ]);
        }
    }

}
