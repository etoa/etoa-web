<?PHP

use App\Support\ForumBridge;

require __DIR__ . '/../../vendor/autoload.php';

// Konfiguration laden
session_start();

dbconnect();

$auth = false;
if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] != "" && $_SERVER['PHP_AUTH_PW'] != "") {
    $user = ForumBridge::userByName($_SERVER['PHP_AUTH_USER']);
    if ($user !== null) {
        if (ForumBridge::authenticateUser($user, $_SERVER['PHP_AUTH_PW'])) {
            $userGroupIds = ForumBridge::groupIdsOfUser($user['id']);
            $allowedGroup = get_config('registered_user_group', 3);
            if (in_array($allowedGroup, $userGroupIds)) {
                $auth = true;
                $_SESSION['etoahelp']['uid'] = $user['id'];
                $_SESSION['etoahelp']['username'] = $user['username'];
                $_SESSION['etoahelp']['email'] = $user['email'];
            }
        }
    }
}
if (!$auth) {
    header("WWW-Authenticate: Basic realm=\"EtoA.ch Hilfe\"");
    header("HTTP/1.0 401 Unauthorized");
}

if ($auth) {
    if (isset($_SERVER["HTTP_REFERER"]) && !preg_match('/\/login/', $_SERVER["HTTP_REFERER"]))
        forward($_SERVER["HTTP_REFERER"]);
    else
        forwardInternal('..');
} else {
    ?>
        <h1>Fehler</h1>
        <p>Du bist nicht eingeloggt!</p>
        <p>Bitte logge dich mit deinem EtoA Forum-Account ein!</p>
        <p>
            <input type="button" value="Neu einloggen" onclick="document.location='.'" />
            <input type="button" value="Account erstellen" onclick="document.location='<?= ForumBridge::url('register') ?>'" />
            <input type="button" value="Zurück" onclick="document.location='..'" />
        </p>
    <?php
}
