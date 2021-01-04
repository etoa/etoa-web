<?PHP
// Used as error page if a file is not found or access has been denied
// see .htaccess for configuration

use App\Support\ForumBridge;

require __DIR__ . '/../vendor/autoload.php';

$e = isset($_GET['e']) ? $_GET['e'] : 404;

switch ($e) {
    case 403:
        $errname = "Fehler 403 - Zugriff verweigert";
        $errtext = "Der Zugriff auf das angeforderte Verzeichnis ist nicht möglich.
    Entweder ist kein Index-Dokument vorhanden oder das Verzeichnis
    ist zugriffsgeschützt.";
        break;
    case 401:
        $errname = "Fehler 401 - Authentisierung fehlgeschlagen";
        $errtext = "Der Server konnte nicht verifizieren, ob Sie autorisiert sind,
    auf diese URL zuzugreifen.
    Entweder wurden falsche Referenzen (z.B. ein falsches Passwort)
    angegeben oder ihr Browser versteht nicht, wie die geforderten
    Referenzen zu übermitteln sind.<br/>
    Sofern Sie für den Zugriff berechtigt sind, überprüfen
    Sie bitte die eingegebene User-ID und das Passwort und versuchen Sie
    es erneut.";
        break;
    default:
        $errname = "Diese Seite wurde leider von einem Schwarzen Loch verschluckt! (Fehler 404)";
        $errtext = "Das tut uns leid! Zum Glück gibt es in unserem Universum noch genügend andere Seiten welche weit genug von Schwarzen Löchern entfernt sind.

			Wähle eine Seite aus folgender Liste:";
}

?>
<!DOCTYPE html>
<html lang="de">
<meta charset="UTF-8" />
<title><?= $errname ?></title>
<style type="text/css">
    body {
        color: #fff;
        background: #000 url('<?= baseUrl('public/images/blackhole.jpg') ?>') no-repeat center 0px;
        font-family: arial, helvetica, verdana;
        font-size: 10pt;
    }

    h1 {
        font-size: 12pt;
    }

    a {
        color: #ddf;
        font-weight: bold;
    }

    a:hover {
        color: #aad;
        font-weight: bold;
        text-decoration: underline;
    }

    p,
    address {
        margin-left: 3em;
    }

    span {
        font-size: smaller;
    }

    .logo {
        text-align: center;
        width: 500px;
        margin: 190px auto 100px;
    }

    .message {
        text-align: center;
        width: 500px;
        margin: 50px auto 50px;
    }

    .links {
        width: 500px;
        margin: 0px auto;
        text-align: center;
    }

    .links ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    .links ul li {
        display: inline-block;
        margin: 0;
        padding: 0;
    }

    .links ul li a {
        text-decoration: none;
        border: 1px solid #3D3D3D;
        border-radius: 2px;
        padding: 8px;
        margin: 5px;
        background: #2C2C2C;
        color: #ccc;
    }

    .links ul li a:hover {
        color: #fff;
        background: #262626;
        border: 1px solid #373737;
    }
</style>
<link rel="shortcut icon" href="<?= baseUrl('favicon.ico') ?>" type="image/x-icon" />
</head>

<body>
    <div class="logo">
        <p><img src="<?= baseUrl('public/images/logo.png') ?>" alt="logo" /></p>
    </div>
    <div class="message">
        <h1><?PHP echo $errname; ?></h1>
        <p><?PHP echo nl2br($errtext); ?></p>
    </div>
    <div class="links">
        <ul>
            <li><a href="<?= baseUrl() ?>">Startseite</a></li>
            <li><a href="<?= ForumBridge::url() ?>">Forum</a></li>
            <li><a href="<?= baseUrl('help') ?>">Hilfecenter</a></li>
            <li><a href="javascript:history.back();">Zurück zur vorherigen Seite</a></li>
        </ul>
    </div>
</body>

</html>
