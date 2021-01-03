<?php

namespace App\Models;

class Faq
{
    public static function withTagId(int $tagId): array
    {
        $res = dbquery("
            SELECT
                faq_question,
                faq_id
            FROM
                " . dbtable('faq') . " f
            INNER JOIN
                " . dbtable('help_tag_rel') . " r
                ON f.faq_id=r.item_id
                AND r.domain = 'faq'
                AND r.tag_id=" . $tagId . "
            ORDER BY faq_question
            ;");
        $items = [];
        while ($arr = mysql_fetch_array($res)) {
            $item = new Faq();
            $item->id = $arr['faq_id'];
            $item->question = $arr['faq_question'];
            $items[] = $item;
        }
        return $items;
    }

    public static function withTagIdAndUserId(int $tagId, int $userId): array
    {
        $res = dbquery("
            SELECT
                faq_question,
                faq_id
            FROM
                " . dbtable('faq') . " f
            INNER JOIN
                " . dbtable('help_tag_rel') . " r
                ON f.faq_id=r.item_id
                AND r.domain = 'faq'
                AND r.tag_id=" . $tagId . "
            LEFT JOIN " . dbtable('faq_comments') . " a ON a.comment_faq_id=f.faq_id
                WHERE
                    (comment_user_id=" . $userId . "
                    OR faq_user_id=" . $userId . ")
            ORDER BY faq_question
            ;");
        $items = [];
        while ($arr = mysql_fetch_array($res)) {
            $item = new Faq();
            $item->id = $arr['faq_id'];
            $item->question = $arr['faq_question'];
            $items[] = $item;
        }
        return $items;
    }

    public static function countByUser(int $userId): int
    {
        $res = dbquery("
            SELECT
                COUNT(faq_id)
            FROM
                " . dbtable('faq') . "
            WHERE
                faq_user_id=" . $userId . "
            ;");
        $arr = mysql_fetch_row($res);
        return $arr[0];
    }

    public static function countActive(): int
    {
        $res = dbquery("
            SELECT
                COUNT(faq_id)
            FROM
                " . dbtable('faq') . "
            WHERE
                faq_deleted=0
            ;");
        $arr = mysql_fetch_row($res);
        return $arr[0];
    }

    public static function latest(int $limit): array
    {
        $res = dbquery("
            SELECT
                faq_question,
                faq_id
            FROM
                " . dbtable('faq') . "
            WHERE
                faq_deleted=0
            ORDER BY faq_updated DESC
            LIMIT " . $limit . "
        ;");
        $items = [];
        while ($arr = mysql_fetch_assoc($res)) {
            $item = new Faq();
            $item->id = $arr['faq_id'];
            $item->question = $arr['faq_question'];
            $items[] = $item;
        }
        return $items;
    }
}
