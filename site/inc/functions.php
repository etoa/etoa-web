<?PHP

include_once 'mysql_polyfill.php';

//////////////////////////////////////////////////
// The Andromeda-Project-Browsergame			//
// Ein Massive-Multiplayer-Online-Spiel			//
// Programmiert von Nicolas Perrenoud		    //
// www.nicu.ch | mail@nicu.ch					//
// als Maturaarbeit '04 am Gymnasium Oberaargau	//
//////////////////////////////////////////////////

/**
 * Mit Datenbank verbinden
 *
 * @param integer $utf8
 * @return void
 */
function dbconnect()
{
    global $db_access;
    global $db_handle;
    if (!$db_handle = @mysql_connect($db_access["server"], $db_access["user"], $db_access["pw"])) {
        echo "</head><body>";
        print_fs_error_msg("Zum Datenbankserver auf <b>" . $db_access['server'] . "</b> kann keine Verbindung hergestellt werden! Bitte schaue später nochmals vorbei.<br/><br/><a href=\"http://forum.etoa.ch\">Zum Forum</a> | <a href=\"mailto:mail@etoa.ch\">Mail an die Spielleitung</a>", "MySQL-Verbindungsproblem");
    }
    if (!@mysql_select_db($db_access["db"])) {
        echo "</head><body>";
        print_fs_error_msg("Auf die Datenbank <b>" . $db_access[db] . "</b> auf <b>" . $db_access[server] . "</b> kann nicht zugegriffen werden! Bitte schaue später nochmals vorbei.<br/><br/><a href=\"http://forum.etoa.ch\">Zum Forum</a> | <a href=\"mailto:mail@etoa.ch\">Mail an die Spielleitung</a>", "MySQL-Verbindungsproblem");
    }
    dbquery("SET NAMES 'utf8';");
}

function print_fs_error_msg($string, $title = "Fehler!")
{
    echo "<table style=\"width:80%;margin:10px auto;border:1px solid #fff;border-collapse:collapse\">";
    echo "<tr><th class=\"tbltitle\">$title</th></tr>";
    echo "<tr><td class=\"tbldata\">$string</td></tr>";
    echo "</table>";
    echo "</body></html>";
    exit;
}

/**
 * Datenbankverbindung trennen
 */
function dbclose()
{
    global $db_handle;
    global $res;
    if (isset($res)) {
        @mysql_free_result($res);
    }
    @mysql_close($db_handle);
}

/**
 * Resolve table name, adding prefix if needed
 *
 * @param string $name
 * @return string
 */
function dbtable($name)
{
    global $db_access;
    return ($db_access['table_prefix'] ?? '') . $name;
}

function wcftable($name)
{
    return 'wcf1_' . $name;
}

function wbbtable($name)
{
    return 'wbb1_1_' . $name;
}

/**
 * Datenbankquery ausführen
 *
 * @param string $string
 * @param integer $fehler
 * @return void|\mysqli_result|bool
 */
function dbquery($string, $fehler = 1)
{
    if ($result = mysql_query($string)) {
        return $result;
    } else {
        if ($fehler == 1) {
            echo "<p><b>Datenbank-Fehler:</b> " . mysql_error() . "!<br><b>Query:</b> $string</p>";
        }
    }
}

function baseUrl()
{
    $str = substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']));
    return substr($str, 0, strrpos($str, "/") + 1);
}

function get_config($key, $default = null, $useCache = true)
{
    static $cache = [];
    if ($useCache && isset($cache[$key])) {
        return $cache[$key];
    }
    $res = dbquery("
        SELECT *
        FROM " . dbtable('config') . "
        WHERE config_name='" . $key . "'
    ;");
    $arr = mysql_fetch_array($res);
    if ($arr != null) {
        $value = stripslashes($arr['config_value']);
        $cache[$key] = $value;
        return $value;
    }
    return $default ?? null;
}

function set_config($key, $value)
{
    unset($_SESSION['config'][$key]);
    dbquery("
        REPLACE INTO " . dbtable('config') . "
        (config_name, config_value)
        VALUES ('" . $key . "', '" . addslashes(trim($value)) . "')
    ;");
}

/**
 * Zahlen formatieren
 *
 * @param int|float $number
 * @return string
 */
function nf($number)
{
    return number_format($number, 0, ".", "'");
}

/**
 * Zeit formatieren
 *
 * @param int $ts
 * @return string
 */
function tf($ts)
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

/**
 * Time format
 *
 * @param int $ts
 * @return string
 */
function tfs($ts)
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
function df($date)
{
    if (date("dmY") == date("dmY", $date))
        $string = "Heute, " . date("H:i", $date);
    else
        $string = date("d.m.y, H:i", $date);
    return $string;
}

/**
 * Format file size to human readable format
 *
 * @param int $file_size
 * @return string
 */
function formatfilesize($file_size)
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
function text2html($string)
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
    $string = preg_replace('/\[img\]([^\[]*)\[\/img\]/i', '<img src="\1" alt="\1" border="0" />', $string);
    $string = preg_replace('/\[img ([0-9]*) ([0-9]*)\]([^\[]*)\[\/img]/i', '<img src="\3" alt="\3" width="\1" height="\2" border="0" />', $string);
    $string = preg_replace('/\[img ([0-9]*)\]([^\[]*)\[\/img]/i', '<img src="\2" alt="\2" width="\1" border="0" />', $string);
    $string = preg_replace('/\[flag ([^\[]*)\]/', '<img src="images/flags/i' . strtolower('\1') . '.gif" border="0" alt="Flagge \1" class=\"flag\" />', $string);
    $string = preg_replace('/\[thumb ([0-9]*)\]([^\[]*)\[\/thumb]/i', '<a href="\2"><img src="\2" alt="\2" width="\1" border="0" /></a>', $string);

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

    //$string=htmlentities($string);

    return $string;
}

function show_text($keyword)
{
    $res = dbquery("
        SELECT *
        FROM " . dbtable('texts') . "
        WHERE text_keyword='" . $keyword . "'
    ;");
    if (mysql_num_rows($res) > 0) {
        $arr = mysql_fetch_array($res);
        if ($arr['text_text'] != "")
            echo text2html($arr['text_text']);
        else
            echo "<p><i><b>Fehler:</b> Texteintrag fehlt!</i></p>";
    } else {
        echo "<p><i><b>Fehler:</b> Datensatz fehlt!</i></p>";
    }
}

function get_gamerounds()
{
    $res = dbquery("
        SELECT *
        FROM " . dbtable('rounds') . "
        WHERE round_active=1 ORDER BY round_name
    ;");
    if (mysql_num_rows($res) > 0) {
        while ($arr = mysql_fetch_array($res)) {
            $rounds[$arr['round_id']]['name'] = $arr['round_name'];
            $rounds[$arr['round_id']]['url'] = $arr['round_url'];
            $rounds[$arr['round_id']]['startdate'] = $arr['round_startdate'];
            $rounds[$arr['round_id']]['active'] = $arr['round_active'];
        }
    }
    return $rounds;
}

function genfkey()
{
    $_SESSION['encfkey'] = rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999);
}

function encfname($name)
{
    return md5($name . $_SESSION['encfkey']);
}

function forward($page, $debug = 0)
{
    if ($debug == 0) {
        header("Location: $page");
    }
    echo "Falls die automatische Weiterleitung nicht klappt, <a href=\"" . $page . "\">hier</a> klicken.";
    exit;
}

function forwardInternal($page, $debug = 0)
{
    forward("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $page, $debug);
}

function pushText($text)
{
    $_SESSION['textstore'] = $text;
}

function popText()
{
    $text = "";
    if (isset($_SESSION['textstore'])) {
        $text = $_SESSION['textstore'];
        unset($_SESSION['textstore']);
    }
    return $text;
}

function message($type, $msg)
{
    return "<div class=\"messagebox\"><div class=\"" . $type . "\">" . $msg . "</div></div>";
}

function prettyUrlString($str)
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
 * Fetches the contents of a JSON config file and returns it as an associative array
 *
 * @param string $file
 * @return array
 */
function fetchJsonConfig($file)
{
    $path = APP_PATH . "/config/" . $file;
    if (!file_exists($path)) {
        throw new Exception("Config file $file not found!");
    }
    $data = json_decode(file_get_contents($path), true);
    if (json_last_error() != JSON_ERROR_NONE) {
        throw new Exception("Failed to parse config file $file (JSON error " . json_last_error() . ")!");
    }
    return $data;
}

function forumUrl($type = null, $value = null, $value2 = null)
{
    $baseUrl = get_config('forum_url');
    if ($type == 'board') {
        return $baseUrl . '/index.php?page=Board&amp;boardID=' . $value;
    }
    if ($type == 'thread') {
        return $baseUrl . '/index.php?page=Thread&amp;threadID=' . $value;
    }
    if ($type == 'post') {
        return $baseUrl . '/index.php?page=Thread&amp;postID=' . $value . '#post' . $value;
    }
    if ($type == 'addpost') {
        return $baseUrl . '/index.php?form=PostAdd&amp;threadID=' . $value;
    }
    if ($type == 'user') {
        return $baseUrl . '/index.php?page=User&amp;userID=' . $value;
    }
    if ($type == 'admin') {
        return $baseUrl . '/acp';
    }
    if ($type == 'account') {
        return $baseUrl . '/index.php?form=AccountManagement';
    }
    if ($type == 'team') {
        return $baseUrl . '/index.php?page=Team';
    }
    if ($type == 'avatar') {
        return $baseUrl . '/wcf/images/avatars/' . ($value > 0
            ? 'avatar-'.$value . "." . $value2
            : 'avatar-default.png');
    }
    return $baseUrl;
}
