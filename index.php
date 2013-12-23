<?PHP
    define('BASE_PATH','');
    define('APP_ID', 'site');
    define('APP_NAME', 'Escape to Andromeda');
    define('APP_PATH', BASE_PATH.APP_ID);
    define('PAGE_PATH', APP_PATH.'/content');
    define('DEFAULT_PAGE', 'news');
    define('DEFAULT_VIEW', 'default');
    define('LAYOUT', APP_ID);
	
	define('FORUM_URL', 'http://forum.etoa.ch');
    
    // Konfiguration laden
    session_start();
    
	$start_time = microtime(true);
	
    include(APP_PATH."/conf.inc.php");
    include(APP_PATH."/functions.php");
    
    // DB Connect
    dbconnect();
    $conf = get_all_config();
    $rounds = get_gamerounds();
    
    // Maintenance
    if ($conf['maintenance_mode']['v']==1)
    {
        include('maintenance/index.html');
        exit;
    }

    // Smarty
    include(BASE_PATH.'lib/smarty/Smarty.class.php');
    $smarty = new Smarty;
    $smarty->setTemplateDir(BASE_PATH.'templates');
    $smarty->setCompileDir(BASE_PATH.'cache/compile');

    $smarty->assign('baseurl', "/");
    $smarty->assign('apppurl', APP_ID."/");

    // Page
    $page = isset($_GET['page']) ? $_GET['page'] : DEFAULT_PAGE;
    $smarty->assign('page', $page);

    // Site name
    $smarty->assign("sitename", ucfirst($page));

    // Content
    $pagepath = PAGE_PATH."/$page.php";
    if (preg_match('/^[a-z0-9_\/\-]+$/i',$page) > 0)
    {
        if (is_file($pagepath))
        {
            $view = $page;
            ob_start();
            include($pagepath);
            $ob = ob_get_clean();

            if ($ob != "")
            {
    		$smarty->assign("content",$ob);
	    }

            $selectedView = is_file(BASE_PATH."templates/views/".APP_ID."/".$view.".html") && preg_match('/^[a-z0-9_\-]+$/i', $view) > 0  ? $view : DEFAULT_VIEW;
            $smarty->assign("content_for_layout", $smarty->fetch('views/'.APP_ID."/".$selectedView.".html"));
	}

        else
        {
            $smarty->assign("error","Seite wurde nicht gefunden!");
            $smarty->assign("content_for_layout", $smarty->fetch("views/".APP_ID."/error.html"));
        }
    }
    else
    {
        $smarty->assign("error","Ung&uuml;ltige Abfrage!");
        $smarty->assign("content_for_layout", $smarty->fetch("views/".APP_ID."/error.html"));
    }

    // Misc stuff

    // Navigation
    $smarty->assign('nav', fetchJsonConfig("nav.conf"));

    // Votebanner
    $smarty->assign('votebanner', stripslashes($conf['buttons']['v']));

    // Login form
    $t = time();
    $logintoken = sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$t).dechex($t);
    $smarty->assign('loginform', array
    (
        'logintoken' => $logintoken,
        'nickField' => sha1("nick".$logintoken.$t),
        'passwordField' => sha1("password".$logintoken.$t),
        'rnd' => mt_rand(10000,99999)
    ));

    // Rounds
    $smarty->assign('rounds', $rounds);
    $smarty->assign('selectedRound', isset($_COOKIE['round']) ? $_COOKIE['round'] : '');

    // Infobox
    ob_start();
    include("site/inc/infobox.inc.php");
    $smarty->assign('infobox', ob_get_clean());
    
    // Adds
    $smarty->assign('adds', $conf['adds']['v']);

	$smarty->assign('footerJs', stripslashes($conf['footer_js']['v']));
    $smarty->assign('headerJs', stripslashes($conf['indexjscript']['v']));
    $smarty->assign('generate_time', round((microtime(true) - $start_time), 3));
    
    // Render
    $smarty->display('layouts/'.LAYOUT.'.html');
    
    dbclose();
?>