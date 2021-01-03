<?php

namespace App\Support;

use App\Models\Text;

class TextUtil
{
    public static function get($keyword)
    {
        $text = Text::findByKeyword($keyword);
        if ($text !== null) {
            if ($text->content != "") {
                return StringUtil::text2html($text->content);
            }
            return "<p><i><b>Fehler:</b> Texteintrag fehlt!</i></p>";
        } else {
            return "<p><i><b>Fehler:</b> Datensatz fehlt!</i></p>";
        }
    }
}
