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
                " . self::wcftable('avatar') . " a
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
                salt,
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
                'salt' => $arr['salt'],
                'email' => $arr['email'],
            ];
        }
        return null;
    }

    public static function authenticateUser(array $user, string $password): bool
    {
        $res = DB::instance('forum')->preparedQuery("
            SELECT
                userID
            FROM
                " . self::wcftable('user') . "
            WHERE
                password = :password
                AND userID = :userId
            ;", [
                'userId' => $user['id'],
                'password' => self::getDoubleSaltedHash($password, $user['salt']),
            ]);
        return is_array($res->fetch());
    }

    /**
	 * Returns a salted hash of the given value.
     *
     * Source: wcf/lib/util/StringUtil.class.php
	 *
	 * @param 	string 		$value
	 * @param	string		$salt
	 * @return 	string 		$hash
	 */
	private static function getSaltedHash($value, $salt) {
		if (!defined('ENCRYPTION_ENABLE_SALTING') || ENCRYPTION_ENABLE_SALTING) {
			$hash = '';
			// salt
			if (!defined('ENCRYPTION_SALT_POSITION') || ENCRYPTION_SALT_POSITION == 'before') {
				$hash .= $salt;
			}

			// value
			if (!defined('ENCRYPTION_ENCRYPT_BEFORE_SALTING') || ENCRYPTION_ENCRYPT_BEFORE_SALTING) {
				$hash .= self::encrypt($value);
			}
			else {
				$hash .= $value;
			}

			// salt
			if (defined('ENCRYPTION_SALT_POSITION') && ENCRYPTION_SALT_POSITION == 'after') {
				$hash .= $salt;
			}

			return self::encrypt($hash);
		}
		else {
			return self::encrypt($value);
		}
	}

	/**
	 * Returns a double salted hash of the given value.
     *
     * Source: wcf/lib/util/StringUtil.class.php
	 *
	 * @param 	string 		$value
	 * @param	string		$salt
	 * @return 	string 		$hash
	 */
	private static function getDoubleSaltedHash($value, $salt) {
		return self::encrypt($salt . self::getSaltedHash($value, $salt));
	}

	/**
	 * encrypts the given value.
     *
     * Source: wcf/lib/util/StringUtil.class.php
	 *
	 * @param 	string 		$value
	 * @return 	string 		$hash
	 */
	private static function encrypt($value) {
		if (defined('ENCRYPTION_METHOD')) {
			switch (ENCRYPTION_METHOD) {
				case 'sha1': return sha1($value);
				case 'md5': return md5($value);
				case 'crc32': return crc32($value);
				case 'crypt': return crypt($value);
			}
		}
		return sha1($value);
	}

    public static function groupIdsOfUser(int $userId): array
    {
        $res = DB::instance('forum')->preparedQuery("
            SELECT
                groupID
            FROM
                " . self::wcftable('user_to_groups') . "
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
                " . self::wcftable('user_to_groups') . " t
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

    public static function usersOnline(int $threshold = 1000): int
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
                        userID
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
