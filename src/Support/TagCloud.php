<?php

namespace App\Support;

class TagCloud
{
    /**
     * Gets items for tag cloud
     *
     * @param int $min_size min font size in pixels
     * @param int $max_size max font size in pixels
     * @return array
     */
    function generate($min_size, $max_size)
    {
        $res = dbquery("
            SELECT
                t.id,
                t.name,
                COUNT(r.item_id) AS cnt
            FROM ".dbtable('help_tag')." t
            INNER JOIN ".dbtable('help_tag_rel')." r
                ON t.id = r.tag_id
            INNER JOIN ".dbtable('faq')." f
                ON r.item_id = f.faq_id
            GROUP BY t.id
            ORDER BY t.name
            ;");
        $tags = array();
        while ($arr = mysql_fetch_assoc($res)) {
            if (!isset($max_qty)) $max_qty = $arr['cnt'];
            if (!isset($min_qty)) $min_qty = $arr['cnt'];
            $max_qty = max($arr['cnt'], $max_qty);
            $min_qty = min($arr['cnt'], $min_qty);
            $tags[] = $arr;
        }
        $spread = $max_qty - $min_qty;
        if ($spread == 0) {
            $spread = 1;
        }
        $step = ($max_size - $min_size) / ($spread);
        $items = [];
        foreach ($tags as $arr) {
            $size = round($min_size + (($arr['cnt'] - $min_qty) * $step));
            $items[] = [
                'id' => $arr['id'],
                'name' => $arr['name'],
                'size' => $size,
                'count' => $arr['cnt'],
            ];
        }
        return $items;
    }
}
