<?PHP

use App\Support\ForumBridge;

echo "<br/><div class=\"boxLine\"></div>";
echo "<div class=\"boxTitle\">Da ist was schiefgegangen...</div>";
echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxData\">";
$err = isset($_GET['err']) ? $_GET['err'] : 'unknown';
switch ($err) {
    case "name":
        echo "<b>Fehler:</b> Du hast vergessen einen Namen oder ein Passwort einzugeben!";
        break;
    case "pass":
        echo "<b>Fehler:</b> Falsches Passwort oder falscher Benutzername!<br/><br/><a href=\"pwrequest\">Passwort vergessen?</a>";
        break;
    case "ip":
        echo "<b>Fehler:</b> IP-Adresse-Überprüfungsfehler! Kein Login von diesem Computer möglich, da schon eine andere IP mit diesem Account verbunden ist!";
        break;
    case "timeout":
        echo "<b>Fehler:</b> Das Timeout wurde erreicht und du wurdest automatisch ausgeloggt!";
        break;
    case "session":
        echo "<b>Fehler:</b> Session-Cookie-Fehler. Überprüfe ob dein Browser wirklich Sitzungscookies akzeptiert!";
        break;
    case "tomanywindows":
        echo "<b>Fehler:</b> Es wurden zu viele Fenster geöffnet oder aktualisiert, dies ist leider nicht erlaubt!";
        break;
    case "session2":
        echo "<b>Fehler:</b> Deine Session ist nicht mehr vorhanden! Sie wurde entweder gelöscht oder sie ist fehlerhaft. Dies kann passieren wenn du dich an einem anderen PC einloggst obwohl du noch mit diesem online warst!";
        break;
    case "nosession":
        echo "<b>Fehler:</b> Deine Session ist nicht mehr vorhanden! Sie wurde entweder gelöscht oder sie ist fehlerhaft. Dies kann passieren wenn du dich an einem anderen PC einloggst obwohl du noch mit diesem online warst!";
        break;
    case "verification":
        echo "<b>Fehler:</b> Falscher Grafikcode! Bitte gib den linksstehenden Code in der Grafik korrekt in das Feld darunter ein!
			Diese Massnahme ist leider nötig um das Benutzen von automatisierten Programmen (Bots) zu erschweren.";
        break;
    case "logintimeout":
        echo "<b>Fehler:</b> Der Login-Schlüssel ist abgelaufen! Bitte logge dich neu ein!";
        break;
    case "sameloginkey":
        echo "<b>Fehler:</b> Der Login-Schlüssel wurde bereits verwendet! Bitte logge dich neu ein!";
        break;
    case "wrongloginkey":
        echo "<b>Fehler:</b> Falscher Login-Schlüssel! Ein Login ist nur von der offiziellen EtoA-Startseite aus möglich!";
        break;
    case "nologinkey":
        echo "<b>Fehler:</b> Kein Login-Schlüssel! Ein Login ist nur von der offiziellen EtoA-Startseite aus möglich!";
        break;
    case "general":
        echo "<b>Fehler:</b> Ein allgemeiner Fehler ist aufgetreten. Bitte den Entwickler kontaktieren!";
        break;
    default:
        echo "<b>Fehler:</b> Unbekannter Fehler (<b>" . $err . "</b>). Bitte den Entwickler kontaktieren!";
}
echo "<br/><br/>Solltest du diesen Fehler nicht lösen können besuche unser <a href=\"help\">HelpCenter</a> <br/>
	oder unser <a href=\"" . ForumBridge::url('board', get_config('support_board')) . "\">Technik-Support-Forum</a>
	um eine Antwort auf dein Problem zu finden!<br/><br/>
	Programmfehler bitte an das <a href=\"https://github.com/etoa/etoa\">Entwickler-Team</a> melden.";
echo "</div>";
echo "<div class=\"boxLine\"></div>";