<?php

use App\Support\ForumBridge;
use App\TemplateEngine;

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Templating engine
$tpl = new TemplateEngine();

// Login
ob_start();
if (isset($_SESSION['etoahelp']['username']) && isset($_SESSION['etoahelp']['uid']) && $_SESSION['etoahelp']['uid'] > 0) {
	define('LOGIN', true);
	echo "<p>Eingeloggt als <b>" . $_SESSION['etoahelp']['username'] . "</b></p>
		<p>
		<a href=\"" . ForumBridge::url('account') . "\">Accountverwaltung</a><br/>
        <a href=\"?page=user&amp;id=" . $_SESSION['etoahelp']['uid'] . "\">Benutzerprofil</a><br/>
        <a href=\"logout\">Logout</a><br/>
		</p>";
} else {
	define('LOGIN', false);
}
$tpl->assign("loginbox", ob_get_clean());

// Content
$page = isset($_GET['page']) ? $_GET['page'] : 'index';
if (preg_match('/^[a-z0-9_\/\-]+$/i', $page) > 0) {
    $pagepath = __DIR__ . "/../content/help/$page.php";
	if (is_file($pagepath)) {
		ob_start();
		require $pagepath;
        $content = ob_get_clean();
		$tpl->assign("content", $content);
		if (isset($header_content)) {
			$tpl->assign("header_content", $header_content);
		}
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

// Site nbame
$tpl->assign("site_title", $site_title ?? ucfirst($page));

// Render
$tpl->render('layouts/help.html');
