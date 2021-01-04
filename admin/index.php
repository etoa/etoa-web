<?php

use App\Support\ForumBridge;
use App\TemplateEngine;

require __DIR__ . '/../vendor/autoload.php';

try {

    redirectHttps();

    // Session
    session_start();

    // Templating engine
    $tpl = new TemplateEngine();

    // Authentication
    $auth = false;
    if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] != "" && $_SERVER['PHP_AUTH_PW'] != "") {
        $user = ForumBridge::userByName($_SERVER['PHP_AUTH_USER']);
        if ($user !== null) {
            if (ForumBridge::authenticateUser($user, $_SERVER['PHP_AUTH_PW'])) {
                $userGroupIds = ForumBridge::groupIdsOfUser($user['id']);
                $allowedGroup = get_config('loginadmin_group', 4);
                if (in_array($allowedGroup, $userGroupIds)) {
                    $auth = true;
                    $_SESSION['etoaadmin']['uid'] = $user['id'];
                    $_SESSION['etoaadmin']['nick'] = $user['username'];
                }
            }
        }
    }
    if (!$auth) {
        header("WWW-Authenticate: Basic realm=\"EtoA Administration\"");
        header("HTTP/1.0 401 Unauthorized");
    }
    $tpl->assign('auth', $auth);

    // Router
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    if (preg_match('/^[a-z0-9_\/\-]+$/i', $page) > 0) {
        $pagepath = __DIR__ . "/../content/admin/$page.php";
        if (is_file($pagepath)) {
            ob_start();
            require $pagepath;
            $content = ob_get_clean();
            $tpl->assign("content", $content);
        } else {
            http_response_code(404);
            $tpl->assign("title", "Fehler");
            $tpl->assign("error", "Seite wurde nicht gefunden!");
        }
    } else {
        http_response_code(400);
        $tpl->assign("title", "Fehler");
        $tpl->assign("error", "Ungültige Abfrage!");
    }

    $tpl->render('layouts/admin.html');

} catch (\PDOException $ex) {
    abort($ex->getMessage(), 'Datenbankfehler');
}
