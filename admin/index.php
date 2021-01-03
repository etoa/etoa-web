<?php

use App\Support\ForumBridge;
use App\TemplateEngine;

require __DIR__ . '/../vendor/autoload.php';

session_start();

dbconnect();

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
    header("WWW-Authenticate: Basic realm=\"EtoA.ch Administration\"");
    header("HTTP/1.0 401 Unauthorized");
}
$tpl->assign('auth', $auth);

// Router
if (isset($_GET['page']) && preg_match("#^[a-z\_]+$#", $_GET['page'])  && strlen($_GET['page']) <= 50) {
    $page = $_GET['page'];
} else {
    $page = "home";
}

// Content
ob_start();
$page_path = "content/" . $page . ".php";
if (file_exists($page_path)) {
    require($page_path);
} else {
    require('content/error404.php');
}
$content = ob_get_clean();
$tpl->assign('content', $content);

$tpl->render('layouts/admin.html');
