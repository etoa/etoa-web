<?php

namespace App\Models;

class Round
{
    public static function all()
    {
        $res = dbquery("
            SELECT *
            FROM " . dbtable('rounds') . "
            ORDER BY round_name
        ;");
        $items = [];
        while ($arr = mysql_fetch_array($res)) {
            $items[] = self::fromArray($arr);
        }
        return $items;
    }

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

            $items[] = self::fromArray($arr);
        }
        return $items;
    }

    public static function fromArray(array $data): Round
    {
        $round = new Round();
        $round->id = $data['round_id'];
        $round->name = $data['round_name'];
        $round->url = $data['round_url'];
        $round->startdate = $data['round_startdate'];
        $round->active = (bool)$data['round_active'];
        return $round;
    }
}
