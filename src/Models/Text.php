<?php

namespace App\Models;

class Text
{
    public static function findByKeyword(string $keyword): ?Text
    {
        $res = dbquery("
            SELECT *
            FROM " . dbtable('texts') . "
            WHERE text_keyword='" . $keyword . "'
        ;");
        if (mysql_num_rows($res) > 0) {
            $arr = mysql_fetch_array($res);
            $item = new Text();
            $item->content = $arr['text_text'];
            return $item;
        }
        return null;
    }
}
