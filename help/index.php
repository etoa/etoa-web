<?php

use App\Support\ForumBridge;
use App\TemplateEngine;

require __DIR__ . '/../vendor/autoload.php';

session_start();

dbconnect();

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
$pagepath = "content/$page.php";
if (preg_match('/^[a-z0-9_\/\-]+$/i', $page) > 0) {
	if (is_file($pagepath)) {
		$view = $page;
		ob_start();
		include($pagepath);
        $content = ob_get_clean();
		$tpl->assign("content", $content);
		if (isset($header_content)) {
			$tpl->assign("header_content", $header_content);
		}
	} else {
		$tpl->assign("title", "Fehler");
		$tpl->assign("error", "Seite wurde nicht gefunden!");
	}
} else {
    $tpl->assign("title", "Fehler");
	$tpl->assign("error", "Ungültige Abfrage!");
}

// Site nbame
$tpl->assign("sitename", "Hilfe | " . ucfirst($page));

// Render
$tpl->render('layouts/help.html');
