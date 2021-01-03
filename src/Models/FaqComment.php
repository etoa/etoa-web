<?php

namespace App\Models;

class FaqComment
{
    public static function countByUser(int $userId): int
    {
        $res = dbquery("
			SELECT
				COUNT(comment_id)
			FROM
                " . dbtable('faq_comments') . "
			WHERE
                comment_user_id=" . $userId . "
            ;");
        $arr = mysql_fetch_row($res);
        return $arr[0];
    }
}
