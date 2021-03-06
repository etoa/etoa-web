<?php

namespace App\Support;

class StringUtil
{
    public static function prettyUrlString(string $str): string
    {
        $ut = $str;
        $ut = str_replace("Ä", "ae", $ut);
        $ut = str_replace("Ö", "oe", $ut);
        $ut = str_replace("Ü", "ue", $ut);
        $ut = str_replace("ä", "ae", $ut);
        $ut = str_replace("ö", "oe", $ut);
        $ut = str_replace("ü", "ue", $ut);
        $ut = str_replace("ß", "ss", $ut);
        $ut = str_replace('/', "-", $ut);
        $ut = str_replace(" ", "-", $ut);
        $ut = str_replace("_", "-", $ut);
        $ut = str_replace("..", "", $ut);
        $ut = str_replace(".", "-", $ut);
        $ut = str_replace("'", "", $ut);
        $ut = str_replace('"', "", $ut);
        $ut = preg_replace("/[^a-z0-9-]/i", "", $ut);
        $ut = strtolower($ut);
        return $ut;
    }

    /**
     *	BB-Code Wrapper
     *
     * @param $string Text to wrap BB-Codes into HTML
     * @return Wrapped text
     *
     * @author MrCage | Nicolas Perrenoud
     *
     * @last editing: Demora | Selina Tanner 04.06.2007
     */
    public static function text2html(string $string): string
    {
        $string = str_replace('&', '&amp;', $string);

        $string = str_replace("  ", "&nbsp;&nbsp;", $string);

        $string = str_replace("\"", "&quot;", $string);
        $string = str_replace("<", "&lt;", $string);
        $string = str_replace(">", "&gt;", $string);

        $string =  preg_replace("((\r\n))", trim('<br/>'), $string);
        $string =  preg_replace("((\n))", trim('<br/>'), $string);
        $string =  preg_replace("((\r)+)", trim('<br/>'), $string);

        $string = str_replace('[b]', '<b>', $string);
        $string = str_replace('[/b]', '</b>', $string);
        $string = str_replace('[B]', '<b>', $string);
        $string = str_replace('[/B]', '</b>', $string);
        $string = str_replace('[i]', '<i>', $string);
        $string = str_replace('[/i]', '</i>', $string);
        $string = str_replace('[I]', '<i>', $string);
        $string = str_replace('[/I]', '</i>', $string);
        $string = str_replace('[u]', '<u>', $string);
        $string = str_replace('[/u]', '</u>', $string);
        $string = str_replace('[U]', '<u>', $string);
        $string = str_replace('[/U]', '</u>', $string);
        $string = str_replace('[c]', '<div style="text-align:center;">', $string);
        $string = str_replace('[/c]', '</div>', $string);
        $string = str_replace('[C]', '<div style="text-align:center;">', $string);
        $string = str_replace('[/C]', '</div>', $string);
        $string = str_replace('[bc]', '<blockquote class="blockquotecode"><code>', $string);
        $string = str_replace('[/bc]', '</code></blockquote>', $string);
        $string = str_replace('[BC]', '<blockquote class="blockquotecode"><code>', $string);
        $string = str_replace('[/BC]', '</code></blockquote>', $string);

        $string = str_replace('[h1]', '<h1>', $string);
        $string = str_replace('[/h1]', '</h1>', $string);
        $string = str_replace('[H1]', '<h1>', $string);
        $string = str_replace('[/H1]', '</h1>', $string);
        $string = str_replace('[h2]', '<h2>', $string);
        $string = str_replace('[/h2]', '</h2>', $string);
        $string = str_replace('[H2]', '<h2>', $string);
        $string = str_replace('[/H2]', '</h2>', $string);
        $string = str_replace('[h3]', '<h3>', $string);
        $string = str_replace('[/h3]', '</h3>', $string);
        $string = str_replace('[H3]', '<h3>', $string);
        $string = str_replace('[/H3]', '</h3>', $string);

        $string = str_replace('[center]', '<div style="text-align:center">', $string);
        $string = str_replace('[/center]', '</div>', $string);
        $string = str_replace('[align=center]', '<div style="text-align:center">', $string);
        $string = str_replace('[/align]', '</div>', $string);
        $string = str_replace('[right]', '<div style="text-align:right">', $string);
        $string = str_replace('[/right]', '</div>', $string);
        $string = str_replace('[headline]', '<div style="text-align:center"><b>', $string);
        $string = str_replace('[/headline]', '</b></div>', $string);

        $string = str_replace('[CENTER]', '<div style="text-align:center">', $string);
        $string = str_replace('[/CENTER]', '</div>', $string);
        $string = str_replace('[RIGHT]', '<div style="text-align:right">', $string);
        $string = str_replace('[/RIGHT]', '</div>', $string);
        $string = str_replace('[HEADLINE]', '<div style="text-align:center"><b>', $string);
        $string = str_replace('[/HEADLINE]', '</b></div>', $string);

        $string = str_replace('[*]', '<li>', $string);
        $string = str_replace('[/*]', '</li>', $string);

        $string = preg_replace('/\[list=1]([^\[]*)\[\/list\]/', '<ol style="list-style-type:decimal">\1</ol>', $string);
        $string = preg_replace('/\[list=a]([^\[]*)\[\/list\]/', '<ol style="list-style-type:lower-latin">\1</ol>', $string);
        $string = preg_replace('/\[list=a]([^\[]*)\[\/list\]/', '<ol style="list-style-type:lower-latin">\1</ol>', $string);
        $string = preg_replace('/\[list=I]([^\[]*)\[\/list\]/', '<ol style="list-style-type:upper-roman">\1</ol>', $string);
        $string = preg_replace('/\[list=i]([^\[]*)\[\/list\]/', '<ol style="list-style-type:upper-roman">\1</ol>', $string);

        $string = str_replace('[list]', '<ul>', $string);
        $string = str_replace('[/list]', '</ul>', $string);
        $string = str_replace('[nlist]', '<ol style="list-style-type:decimal">', $string);
        $string = str_replace('[/nlist]', '</ol>', $string);
        $string = str_replace('[alist]', '<ol style="list-style-type:lower-latin">', $string);
        $string = str_replace('[/alist]', '</ol>', $string);
        $string = str_replace('[rlist]', '<ol style="list-style-type:upper-roman">', $string);
        $string = str_replace('[/rlist]', '</ol>', $string);

        $string = str_replace('[LIST]', '<ul>', $string);
        $string = str_replace('[/LIST]', '</ul>', $string);
        $string = str_replace('[NLIST]', '<ol style="list-style-type:decimal">', $string);
        $string = str_replace('[/NLIST]', '</ol>', $string);
        $string = str_replace('[ALIST]', '<ol style="list-style-type:lower-latin">', $string);
        $string = str_replace('[/ALIST]', '</ol>', $string);
        $string = str_replace('[RLIST]', '<ol style="list-style-type:upper-roman">', $string);
        $string = str_replace('[/RLIST]', '</ol>', $string);

        $string = str_replace('[element]', '<li>', $string);
        $string = str_replace('[/element]', '</li>', $string);
        $string = str_replace('[ELEMENT]', '<li>', $string);
        $string = str_replace('[/ELEMENT]', '</li>', $string);

        $string = str_replace('[line]', '<hr class="line" />', $string);
        $string = str_replace('[LINE]', '<hr class="line" />', $string);

        $string = preg_replace('/\[codebox ([^\[]*) ([^\[]*)\]/', '<textarea readonly=\"readonly\" rows=\"\1\" cols=\"\2\">', $string);

        $string = str_replace('[codebox]', '<textarea readonly=\"readonly\" rows=\"3\" cols=\"60\">', $string);
        $string = str_replace('[/codebox]', '</textarea>', $string);

        $string = preg_replace('/\[quote]([^\[]*)\[\/quote\]/i', '<fieldset class="quote"><legend class="quote"><b>Zitat</b></legend>\1</fieldset>', $string);
        $string = preg_replace('/\[quote ([^\[]*)\]([^\[]*)\[\/quote\]/i', '<fieldset class="quote"><legend class="quote"><b>Zitat von:</b> \1</legend>\2</fieldset>', $string);
        $string = preg_replace('/\[quote=([^\[]*)\]([^\[]*)\[\/quote\]/i', '<fieldset class="quote"><legend class="quote"><b>Zitat von:</b> \1</legend>\2</fieldset>', $string);
        $string = preg_replace('/\[img\]([^\[]*)\[\/img\]/i', '<img src="\1" alt="\1" style="border: 0" />', $string);
        $string = preg_replace('/\[img ([0-9]*) ([0-9]*)\]([^\[]*)\[\/img]/i', '<img src="\3" alt="\3" width="\1" height="\2"  style="border: 0" />', $string);
        $string = preg_replace('/\[img ([0-9]*)\]([^\[]*)\[\/img]/i', '<img src="\2" alt="\2" width="\1" style="border: 0" />', $string);
        $string = preg_replace('/\[flag ([^\[]*)\]/', '<img src="images/flags/i' . strtolower('\1') . '.gif" style="border: 0" alt="Flagge \1" class=\"flag\" />', $string);
        $string = preg_replace('/\[thumb ([0-9]*)\]([^\[]*)\[\/thumb]/i', '<a href="\2"><img src="\2" alt="\2" width="\1" style="border: 0" /></a>', $string);

        $string = preg_replace("/^http:\/\/([^ ,\n]*)/", "[url]http://\\1[/url]", $string);
        $string = preg_replace("/^ftp:\/\/([^ ,\n]*)/", "[url]ftp://\\1[/url]", $string);
        $string = preg_replace("/^www\\.([^ ,\n]*)/", "[url]http://www.\\1[/url]", $string);

        $string = preg_replace('/\[url=\'([^\[]*)\'\]([^\[]*)\[\/url\]/i', '<a href="\1">\2</a>', $string);
        $string = preg_replace('/\[url=([^\[]*)\]([^\[]*)\[\/url\]/i', '<a href="\1">\2</a>', $string);
        $string = preg_replace('/\[url ([^\[]*)\]([^\[]*)\[\/url\]/i', '<a href="\1">\2</a>', $string);
        $string = preg_replace('/\[url\]www.([^\[]*)\[\/url\]/i', '<a href="http://www.\1">\1</a>', $string);
        $string = preg_replace('/\[url\]([^\[]*)\[\/url\]/i', '<a href="\1">\1</a>', $string);

        $string = preg_replace('/\[mailurl=([^\[]*)\]([^\[]*)\[\/mailurl\]/i', '<a href="mailto:\1">\2</a>', $string);
        $string = preg_replace('/\[mailurl ([^\[]*)\]([^\[]*)\[\/mailurl\]/i', '<a href="mailto:\1">\2</a>', $string);
        $string = preg_replace('/\[mailurl\]([^\[]*)\[\/mailurl\]/i', '<a href="mailto:\1">\1</a>', $string);
        $string = preg_replace('/\[email=([^\[]*)\]([^\[]*)\[\/email\]/i', '<a href="mailto:\1">\2</a>', $string);
        $string = preg_replace('/\[email ([^\[]*)\]([^\[]*)\[\/email\]/i', '<a href="mailto:\1">\2</a>', $string);
        $string = preg_replace('/\[email\]([^\[]*)\[\/email\]/i', '<a href="mailto:\1">\1</a>', $string);

        $string = preg_replace('/== ([^\[]*) ==/', '<h3>\1</h3>', $string);
        $string = preg_replace('/= ([^\[]*) =/', '<h2>\1</h2>', $string);

        $string = str_replace('[table]', '<table class="bbtable">', $string);
        $string = str_replace('[/table]', '</table>', $string);
        $string = str_replace('[td]', '<td>', $string);
        $string = str_replace('[/td]', '</td>', $string);
        $string = str_replace('[th]', '<th>', $string);
        $string = str_replace('[/th]', '</th>', $string);
        $string = str_replace('[tr]', '<tr>', $string);
        $string = str_replace('[/tr]', '</tr>', $string);

        $string = str_replace('[TABLE]', '<table>', $string);
        $string = str_replace('[/TABLE]', '</table>', $string);
        $string = str_replace('[TD]', '<td>', $string);
        $string = str_replace('[/TD]', '</td>', $string);
        $string = str_replace('[TH]', '<th>', $string);
        $string = str_replace('[/TH]', '</th>', $string);
        $string = str_replace('[TR]', '<tr>', $string);
        $string = str_replace('[/TR]', '</tr>', $string);

        $string = preg_replace('/\[font ([^\[]*)\]/i', '<span style=\"font-family:\1">', $string);
        $string = preg_replace('/\[color ([^\[]*)\]/i', '<span style=\"color:\1">', $string);
        $string = preg_replace('/\[size ([^\[]*)\]/i', '<span style=\"font-size:\1pt">', $string);
        $string = preg_replace('/\[font=([^\[]*)\]/i', '<span style=\"font-family:\1">', $string);
        $string = preg_replace('/\[color=([^\[]*)\]/i', '<span style=\"color:\1">', $string);
        $string = preg_replace('/\[size=([^\[]*)\]/i', '<span style=\"font-size:\1pt">', $string);
        $string = str_replace('[/font]', '</span>', $string);
        $string = str_replace('[/FONT]', '</span>', $string);
        $string = str_replace('[/color]', '</span>', $string);
        $string = str_replace('[/COLOR]', '</span>', $string);
        $string = str_replace('[/size]', '</span>', $string);
        $string = str_replace('[/SIZE]', '</span>', $string);

        $string = stripslashes($string);

        return $string;
    }

    /**
     * Zahlen formatieren
     *
     * @param int|float $number
     * @return string
     */
    public static function numberFormat($number): string
    {
        return number_format($number, 0, ".", "'");
    }

    /**
     * Zeit formatieren
     *
     * @param int $ts
     * @return string
     */
    public static function timeFormat(int $ts): string
    {
        $t = floor($ts / 3600 / 24);
        $h = floor(($ts - ($t * 24 * 3600)) / 3600);
        $m = floor(($ts - ($t * 24 * 3600) - ($h * 3600)) / 60);
        $s = floor(($ts - ($t * 24 * 3600) - ($h * 3600) - ($m * 60)));

        $str = "";
        if ($t > 0) $str .= $t . "d ";
        if ($h > 0) $str .= $h . "h ";
        if ($m > 0) $str .= $m . "m ";
        if ($s > 0) $str .= $s . "s ";
        return $str;
    }

    public static function diffFromNow(int $time): string
    {
        return self::timeDiffFormat(time() - $time);
    }

    /**
     * Time format
     *
     * @param int $ts
     * @return string
     */
    public static function timeDiffFormat(int $ts): string
    {
        if ($ts < 60)
            return "vor " . $ts . " s";
        if ($ts < 3600)
            return "vor " . ceil($ts / 60) . " m";
        $tm = time();
        $mn = mktime(0, 0, 0, date("m", $tm), date("d", $tm), date("Y", $tm));
        if ($ts < $tm - $mn)
            return "vor " . ceil($ts / 3600) . " h";
        if ($ts - 86400 < $tm - $mn)
            return "gestern";
        if ($ts - (86400 * 2) < $tm - $mn)
            return "vor 2 Tagen";
        if ($ts - (86400 * 3) < $tm - $mn)
            return "vor 3 Tagen";
        return date("d.m.y", $tm - $ts);
    }

    /**
     * Datum formatieren
     *
     * @param int $date
     * @return string
     */
    public static function dateFormat(int $date): string
    {
        if (date("dmY") == date("dmY", $date)) {
            return "Heute, " . date("H:i", $date);
        }
        return date("d.m.y, H:i", $date);
    }

    /**
     * Format file size to human readable format
     *
     * @param int $file_size
     * @return string
     */
    public static function formatfilesize(int $file_size): string
    {
        if ($file_size < pow(1024, 1)) $file_size = $file_size . " Byte";
        elseif ($file_size < pow(1024, 2)) {
            $file_size = round($file_size / pow(1024, 1), 2);
            $file_size = $file_size . " KB";
        } elseif ($file_size < pow(1024, 3)) {
            $file_size = round($file_size / pow(1024, 2), 2);
            $file_size = $file_size . " MB";
        } elseif ($file_size < pow(1024, 4)) {
            $file_size = round($file_size / pow(1024, 2), 3);
            $file_size = $file_size . " GB";
        } elseif ($file_size < pow(1024, 5)) {
            $file_size = round($file_size / pow(1024, 2), 4);
            $file_size = $file_size . " TB";
        }
        return $file_size;
    }
}
