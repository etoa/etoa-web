<?PHP

use Adbar\Dot;

require_once 'mysql_polyfill.php';

/**
 * Fetches a config value from the site config file
 *
 * @param string $key key in dot notation
 * @param mixed $default
 * @return mixed
 */
function config(string $key, $default = null)
{
    $file = __DIR__ . "/../config/app.php";
    if (!is_file($file)) {
        abort('Konfigurationsdatei <strong>' . $file . '</strong> existiert nicht.');
    }
    $config = require $file;
    $dot = new Dot($config);
    return $dot->get($key, $default);
}

/**
 * Aborts the request and shows a simple error message
 *
 * @param string $message
 * @param string $title
 * @return void
 */
function abort(string $message, string $title = 'Error')
{
    ob_clean();
    http_response_code(500);
    echo '<h1>' . $title . '</h1><p>' . $message . '</p>';
    exit;
}

/**
 * Mit Datenbank verbinden
 *
 * @param integer $utf8
 * @return void
 */
function dbconnect()
{
    global $db_handle;
    $host = config('database.host');
    $user = config('database.user');
    $password = config('database.password');
    $database = config('database.database');
    if (!$db_handle = @mysql_connect($host, $user, $password)) {
        abort("Zum Datenbankserver auf <strong>" . $host . "</strong> kann keine Verbindung hergestellt werden! Bitte schaue später nochmals vorbei.", "MySQL-Verbindungsproblem");
    }
    if (!mysql_select_db($database)) {
        abort("Auf die Datenbank <strong>" . $database . "</strong> auf <b>" . $host . "</b> kann nicht zugegriffen werden! Bitte schaue später nochmals vorbei.", "MySQL-Verbindungsproblem");
    }
    dbquery("SET NAMES 'utf8';");
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
    return config('database.table_prefix', '') . $name;
}

/**
 * Datenbankquery ausführen
 *
 * @param string $string
 * @param integer $fehler
 * @return void|\mysqli_result|bool
 */
function dbquery($string)
{
    global $db_handle;
    if (!isset($db_handle)) {
        dbconnect();
    }
    if ($result = mysql_query($string)) {
        return $result;
    }
    abort(mysql_error() . '<br><br><strong>Query:</strong> ' . $string, 'Datenbank-Fehler');
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

function baseUrl(): string
{
    $str = substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']));
    $url = substr($str, 0, strrpos($str, "/") + 1);
    return $url;
}

function helpUrl($page, $key = null, $value = null): string
{
    return baseUrl() . 'help/?page=' . $page . '&amp;' . $key . '=' . $value;
}
