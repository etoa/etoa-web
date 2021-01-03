<?php
ini_set('arg_separator.output',  '&amp;');
session_start();

$auth = false;

// Zufallsgenerator initialisieren
mt_srand(time());

// Konfiguration laden
include("../site/config/conf.inc.php");
include("../site/inc/functions.php");

// Mit der DB verbinden und Config-Werte laden
dbconnect();

$error = "Nicht eingeloggt!	";

if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] != "" && $_SERVER['PHP_AUTH_PW'] != "") {
    define('WCF_DIR', '../forum/wcf/');
    require(WCF_DIR . "lib/util/StringUtil.class.php");

    $res = dbquery("
            SELECT
                userID,
                username,
                salt
            FROM
                " . wcftable('user') . "
            WHERE
                username='" . $_SERVER['PHP_AUTH_USER'] . "'
		;");
    if (mysql_num_rows($res) > 0) {
        $arr = mysql_Fetch_array($res);
        $cres = dbquery("
                SELECT
                    userID
                FROM
                    " . wcftable('user') . "
                WHERE
                    password='" . StringUtil::getDoubleSaltedHash($_SERVER['PHP_AUTH_PW'], $arr['salt']) . "'
                AND
                    userID='" . $arr['userID'] . "'
			;");
        if (mysql_num_rows($cres) > 0) {
            $gcheck = false;
            $res = dbquery("
                    SELECT
                        groupID
                    FROM
                        " . wcftable('user_to_groups') . "
                    WHERE
                        userID='" . $arr['userID'] . "'
				;");
            while ($garr = mysql_fetch_row($res)) {
                if (get_config('loginadmin_group') == $garr[0]) {
                    $gcheck = true;
                    break;
                }
            }
            if ($gcheck) {
                $auth = true;
                $_SESSION['etoaadmin']['uid'] = $arr['userID'];
                $_SESSION['etoaadmin']['nick'] = $arr['username'];
            } else {
                $error = "Keine Berechtigung!";
            }
        } else {
            $error = "User nicht vorhanden oder Passwort falsch!";
        }
    } else {
        $error = "User nicht vorhanden oder Passwort falsch!";
    }
}

if (!$auth) {
    header("WWW-Authenticate: Basic realm=\"EtoA.ch Administration\"");
    header("HTTP/1.0 401 Unauthorized");
}

// Standardseite
if (isset($_GET['page']) && preg_match("#^[a-z\_]+$#", $_GET['page'])  && strlen($_GET['page']) <= 50) {
    $page = $_GET['page'];
} else {
    $page = "home";
}

ob_start();
$page_path = "content/" . $page . ".php";
if (file_exists($page_path)) {
    require($page_path);
} else {
    echo '<h1>Fehler</h1>Seite existiert nicht!';
}
$content = ob_get_clean();

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <title>EtoA | Das Sci-Fi Browsergame | Administration</title>
    <meta name="author" content="EtoA Gaming" />
    <meta name="description" content="EtoA - Das kostenlose Sci-Fi Browsergame." />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="overall">
        <div id="header">
            <div id="logo" onclick="javascript: document.location.href='./';">
            </div>
            <div id="bar"></div>
        </div>
        <div id="main">
            <div id="menu">
                <div id="menu_top"></div>
                <div id="menu_center">
                    <?php if ($auth) : ?>
                        <b>Navigation</b><br />
                        <a href="?page=home">&Uuml;bersicht</a>
                        <a href="?page=status">Statusmeldungen</a>
                        <a href="?page=serverinfo">Servermeldung</a>
                        <a href="?page=maintenance">Wartungsmodus</a>
                        <a href="?page=rounds">Runden</a>
                        <a href="?page=buttons">Buttons</a>
                        <a href="?page=adds">Werbung</a>
                        <a href="?page=script">Java-Script</a>
                        <a href="?page=faq">FAQ</a>
                        <a href="?page=articles">Hilfe-Artikel</a>
                        <a href="?page=texts">Texte</a>
                        <a href="?page=settings">Einstellungen</a>
                    <?php endif; ?>
                </div>
                <div id="menu_bottom"></div>
            </div>
            <div id="content">
                <div>
                    <?PHP
                    if ($auth) {
                        echo $content;
                    } else {
                        echo "<h1>Fehler:</h1> " . $error . "<br/><br/>";
                        echo "<input type=\"button\" value=\"Neu einloggen\" onclick=\"document.location='?page=$page'\" />";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="overallend">
        </div>
        <div id="footer">
            <div id="server">&copy; EtoA Gaming</div>
        </div>
    </div>
</body>

</html>
