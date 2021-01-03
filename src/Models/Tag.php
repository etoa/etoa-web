<?php

namespace App\Models;

class Tag
{
    public static function find(int $id): ?Tag
    {
        $res = dbquery("
            SELECT
                name
            FROM
                " . dbtable('help_tag') . "
            WHERE
                id=" . intval($id) . ";");
        if (mysql_num_rows($res) > 0) {
            $arr = mysql_fetch_array($res);
            $item = new Tag();
            $item->id = $id;
            $item->name = $arr['name'];
            return $item;
        }
        return null;
    }

    public static function popular(int $limit): array
    {
        $res = dbquery("
            SELECT * FROM (
                SELECT
                    t.id,
                    t.name,
                    COUNT(r.item_id) AS cnt
                FROM " . dbtable('help_tag') . " t
                INNER JOIN " . dbtable('help_tag_rel') . " r ON t.id=r.tag_id
                INNER JOIN " . dbtable('faq') . " f ON r.item_id=f.faq_id
                GROUP BY t.id
                ORDER BY cnt DESC LIMIT " . $limit . "
            ) AS t
            ORDER BY t.name
        ;");
        $items = [];
        while ($arr = mysql_fetch_assoc($res)) {
            $item = new Tag();
            $item->id = $arr['id'];
            $item->name = $arr['name'];
            $item->count = $arr['cnt'];
            $items[] = $item;
        }
        return $items;
    }
}
