<?PHP

use Adbar\Dot;
use App\Models\Round;

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
    global $db_driver;
    $db_driver = 'default';
    $host = config("database.$db_driver.host");
    $user = config("database.$db_driver.user");
    $password = config("database.$db_driver.password");
    $database = config("database.$db_driver.database");
    $charset = config("database.$db_driver.charset", 'utf8');
    if (!$db_handle = @mysql_connect($host, $user, $password)) {
        abort("Zum Datenbankserver auf <strong>" . $host . "</strong> kann keine Verbindung hergestellt werden! Bitte schaue später nochmals vorbei.", "MySQL-Verbindungsproblem");
    }
    if (!mysql_select_db($database)) {
        abort("Auf die Datenbank <strong>" . $database . "</strong> auf <b>" . $host . "</b> kann nicht zugegriffen werden! Bitte schaue später nochmals vorbei.", "MySQL-Verbindungsproblem");
    }
    dbquery("SET NAMES '$charset';");
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
    global $db_driver;
    return config("database.$db_driver.table_prefix", '') . $name;
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

function redirectHttps()
{
    if ($_SERVER['HTTP_HOST'] != 'localhost') {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
            $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $location);
            exit;
        }
    }
}

function message($type, $msg)
{
    return "<div class=\"messagebox\"><div class=\"" . $type . "\">" . $msg . "</div></div>";
}

function baseUrl($path = null): string
{
    $str = substr(realpath(__DIR__ . '/../'), strlen(realpath($_SERVER['DOCUMENT_ROOT'])));
    $url = str_replace(DIRECTORY_SEPARATOR, '/', $str) . '/';
    return $url . ($path ?? '');
}

function loginRoundUrl(Round $round, string $page)
{
    return $round->url . '/show.php?index=' . $page;
}

function getAppBasePath(): string {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $uri = (string) parse_url('http://a' . $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if (stripos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
        return $_SERVER['SCRIPT_NAME'];
    }
    if ($scriptDir !== '/' && stripos($uri, $scriptDir) === 0) {
        return $scriptDir;
    }
    return '';
}
