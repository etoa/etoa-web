<?php

namespace App\Models;

class Article
{
    public static function withTagId(int $tagId): array
    {
        $res = dbquery("
            SELECT * FROM (SELECT
                w.title,
                w.hash,
                w.id
            FROM
                " . dbtable('articles') . " w
            INNER JOIN
                " . dbtable('help_tag_rel') . " r
                ON w.id=r.item_id
                AND r.domain = 'wiki'
                AND r.tag_id=" . $tagId . "
            ORDER BY w.hash,w.rev DESC) a
            GROUP BY a.hash
            ORDER BY title
            ;");
        $items = [];
        while ($arr = mysql_fetch_array($res)) {
            $item = new Article();
            $item->id = $arr['id'];
            $item->title = $arr['title'];
            $item->hash = $arr['hash'];
            $items[] = $item;
        }
        return $items;
    }

    public static function countByUser(int $userId): int
    {
        $res = dbquery("
            SELECT
                COUNT(id)
            FROM
                " . dbtable('articles') . "
            WHERE
                user_id='" . $userId . "'
            ;");
        $arr = mysql_fetch_row($res);
        return $arr[0];
    }

    public static function count(): int
    {
        $res = dbquery("
            SELECT
                COUNT(*)
            FROM
                " . dbtable('articles') . "
            ;");
        $arr = mysql_fetch_row($res);
        return $arr[0];
    }
}
