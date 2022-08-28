<?php

namespace App\Support;

class StringUtil
{
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
}
