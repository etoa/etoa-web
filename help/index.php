<?PHP
	define('BASE_PATH','../');

	// Konfiguration laden
	session_start();
	include(BASE_PATH."site/conf.inc.php");
	include(BASE_PATH."site/functions.php");
	dbconnect();
	$conf = get_all_config();

	// Forum
	define('FORUM_URL', 'http://forum.etoa.ch');
	
	// Smarty
	include(BASE_PATH.'lib/smarty/Smarty.class.php');
	$smarty = new Smarty;
	$smarty->setTemplateDir(BASE_PATH.'help/templates');
	$smarty->setCompileDir(BASE_PATH.'cache/compile');
	
	$smarty->assign('baseurl',"");	
	
	// Tag cloud
	ob_start();
	$res = dbquery("SELECT t.id,t.name,COUNT(r.item_id) AS cnt
	FROM help_tag t
	INNER JOIN help_tag_rel r ON t.id=r.tag_id
	INNER JOIN faq f ON r.item_id=f.faq_id
	GROUP BY t.id
	ORDER BY t.name
	;");
	$tags = array();
	while ($arr = mysql_fetch_assoc($res))
	{
		if (!isset($max_qty)) $max_qty = $arr['cnt'];
		if (!isset($min_qty)) $min_qty = $arr['cnt'];
		$max_qty = max($arr['cnt'],$max_qty);
		$min_qty = min($arr['cnt'],$min_qty);
		$tags[] = $arr;
	}
	$max_size = 20; // max font size in pixels
	$min_size = 10; // min font size in pixels
	$spread = $max_qty - $min_qty;
	if ($spread == 0) { 
			$spread = 1;
	}
	$step = ($max_size - $min_size) / ($spread);
	foreach ($tags as $arr) 
	{
		$size = round($min_size + (($arr['cnt'] - $min_qty) * $step));
		echo "<a style=\"font-size: ".$size."px\" href=\"?page=tags&amp;id=".$arr['id']."\" title=\"".$arr['cnt']." Einträge getaggt mit '".$arr['name']."'\">".$arr['name']."</a> ";
	}	
	$smarty->assign("tagcloud",ob_get_clean());	

	// Login
	ob_start();
	if (isset($_SESSION['etoahelp']['username']) && isset($_SESSION['etoahelp']['uid']) && $_SESSION['etoahelp']['uid'] > 0)
	{
		define('LOGIN',true);
		echo "<p>Eingeloggt als <b>".$_SESSION['etoahelp']['username']."</b></p>
		<p>
		<a href=\"".FORUM_URL."/index.php?form=AccountManagement\">Accountverwaltung</a><br/>
		<a href=\"?page=user&amp;id=".$_SESSION['etoahelp']['uid']."\">Benutzerprofil</a><br/>
		</p>";
	}
	else
		define('LOGIN',false);
	$smarty->assign("loginbox",ob_get_clean());	
	
	// Content
	$page = isset($_GET['page']) ? $_GET['page'] : 'index';
	$pagepath = "content/$page.php";
	if (preg_match('/^[a-z0-9_\/\-]+$/i',$page) > 0)
	{
		if (is_file($pagepath))
		{
			$view = $page;
			ob_start();
			include($pagepath);
			$ob = ob_get_clean();
			if ($ob != "")
				$smarty->assign("content",$ob);
			$selectedView = is_file($smarty->getTemplateDir()."/views/help/$view.html") && preg_match('/^[a-z0-9_\-]+$/i',$view) > 0  ? $view : 'default';
			$smarty->assign("content_for_layout",$smarty->fetch("views/help/$selectedView.html"));
		}
		else
		{
			$smarty->assign("error","Seite wurde nicht gefunden!");
			$smarty->assign("content_for_layout",$smarty->fetch("views/help/error.html"));
		}
	}
	else
	{
		$smarty->assign("error","Ung&uuml;ltige Abfrage!");
		$smarty->assign("content_for_layout",$smarty->fetch("views/help/error.html"));
	}	
	
	// Site nbame
	$smarty->assign("sitename","Hilfe | ".ucfirst($page));
	
	// Render
	$smarty->display('layouts/help.html');
?>		  