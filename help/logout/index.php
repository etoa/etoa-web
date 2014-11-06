<?PHP
	define('SITE_URL',"../");

	define('BASE_PATH','../../');

	// Konfiguration laden
	session_start();
	include(BASE_PATH."site/conf.inc.php");
	include(BASE_PATH."site/functions.php");
	
	header("WWW-Authenticate: Basic realm=\"EtoA.ch Hilfe\"");
	header("HTTP/1.0 401 Unauthorized"); 
	unset($_SESSION['etoahelp']);
	
	if (isset($_SERVER["HTTP_REFERER"]))
		forward($_SERVER["HTTP_REFERER"]);	
	else
		forwardInternal(SITE_URL);	
?>