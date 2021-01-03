<?php

namespace App\Models;

class Round
{
    public static function active()
    {
        $res = dbquery("
            SELECT *
            FROM " . dbtable('rounds') . "
            WHERE round_active = 1
            ORDER BY round_name
        ;");
        $items = [];
        while ($arr = mysql_fetch_array($res)) {
            $item = new Round();
            $item->id = $arr['round_id'];
            $item->name = $arr['round_name'];
            $item->url = $arr['round_url'];
            $item->startdate = $arr['round_startdate'];
            $item->active = (bool)$arr['round_active'];
            $items[] = $item;
        }
        return $items;
    }
}
