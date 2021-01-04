<?PHP
$messages = [
    "name" => "Du hast vergessen einen Namen oder ein Passwort einzugeben!",
    "pass" => "Falsches Passwort oder falscher Benutzername!<br/><br/><a href=\"pwrequest\">Passwort vergessen?</a>",
    "ip" => "IP-Adresse-Überprüfungsfehler! Kein Login von diesem Computer möglich, da schon eine andere IP mit diesem Account verbunden ist!",
    "timeout" => "Das Timeout wurde erreicht und du wurdest automatisch ausgeloggt!",
    "session" => "Session-Cookie-Fehler. Überprüfe ob dein Browser wirklich Sitzungscookies akzeptiert!",
    "tomanywindows" => "Es wurden zu viele Fenster geöffnet oder aktualisiert, dies ist leider nicht erlaubt!",
    "session2" => "Deine Session ist nicht mehr vorhanden! Sie wurde entweder gelöscht oder sie ist fehlerhaft. Dies kann passieren wenn du dich an einem anderen PC einloggst obwohl du noch mit diesem online warst!",
    "nosession" => "Deine Session ist nicht mehr vorhanden! Sie wurde entweder gelöscht oder sie ist fehlerhaft. Dies kann passieren wenn du dich an einem anderen PC einloggst obwohl du noch mit diesem online warst!",
    "verification" => "Falscher Grafikcode! Bitte gib den linksstehenden Code in der Grafik korrekt in das Feld darunter ein! Diese Massnahme ist leider nötig um das Benutzen von automatisierten Programmen (Bots) zu erschweren.",
    "logintimeout" => "Der Login-Schlüssel ist abgelaufen! Bitte logge dich neu ein!",
    "sameloginkey" => "Der Login-Schlüssel wurde bereits verwendet! Bitte logge dich neu ein!",
    "wrongloginkey" => "Falscher Login-Schlüssel! Ein Login ist nur von der offiziellen EtoA-Startseite aus möglich!",
    "nologinkey" => "Kein Login-Schlüssel! Ein Login ist nur von der offiziellen EtoA-Startseite aus möglich!",
    "general" => "Ein allgemeiner Fehler ist aufgetreten. Bitte den Entwickler kontaktieren!",
    "default" => "Unbekannter Fehler. Bitte den Entwickler kontaktieren!",
];
$err = isset($_GET['err']) && preg_match('/^[a-z0-9]+$/', $_GET['err']) ? $_GET['err'] : 'unknown';
?>

<br/>
<div class="boxLine"></div>
<div class="boxTitle">Da ist was schiefgegangen...</div>
<div class="boxLine"></div>
<div class="boxData">
    <strong><?= $messages[$err] ?? $messages['default'] ?></strong><br><br>
    Solltest du diesen Fehler nicht lösen können besuche unser <a href="help">HelpCenter</a> <br/>
    oder unser <a href="<?= App\Support\ForumBridge::url('board', get_config('support_board')) ?>">Technik-Support-Forum</a>
    um eine Antwort auf dein Problem zu finden!<br/><br/>
    Programmfehler bitte an das <a href="https://github.com/etoa/etoa">Entwickler-Team</a> melden.
</div>
<div class="boxLine"></div>
