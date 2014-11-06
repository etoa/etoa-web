<?PHP
	define('USER_LOGIN_GROUP',3);
	define('SITE_URL',"../");

	define('BASE_PATH','../../');

	// Konfiguration laden
	session_start();
	include(BASE_PATH."site/conf.inc.php");
	include(BASE_PATH."site/functions.php");
	dbconnect();
	$conf = get_all_config();

	// Smarty
	include(BASE_PATH.'lib/smarty/Smarty.class.php');
	$smarty = new Smarty;
	$smarty->setTemplateDir(BASE_PATH.'templates');
	$smarty->setCompileDir(BASE_PATH.'cache/compile');
	
	$smarty->assign('baseurl',"");	
	
	ob_start();
	$error="Du bist nicht eingeloggt!";

	if ($_SERVER['PHP_AUTH_USER']!="" && $_SERVER['PHP_AUTH_PW']!="")
	{
		define('WCF_DIR','../../forum/wcf/');
		require(WCF_DIR."lib/util/StringUtil.class.php");
		
		$res = dbquery("
		SELECT
			userID,
			username,
			email,
			salt
		FROM
			wcf1_user
		WHERE
			username='".$_SERVER['PHP_AUTH_USER']."'
		;");
		if (mysql_num_rows($res)>0)
		{
			$arr = mysql_Fetch_assoc($res);
			$cres = dbquery("
			SELECT
				userid
			FROM
				wcf1_user
			WHERE
				password='".StringUtil::getDoubleSaltedHash($_SERVER['PHP_AUTH_PW'], $arr['salt'])."'
			;");
			if (mysql_num_rows($cres)>0)	
			{
				$gcheck = false;
				$gres = dbquery("
				SELECT
					groupID
				FROM
					wcf1_user_to_groups
				");
				while ($garr=mysql_fetch_row($gres))
				{
					if (USER_LOGIN_GROUP == $garr[0])
					{
						$gcheck = true;
						break;
					}
				}
				if ($gcheck)
				{
					$auth = true;
					$_SESSION['etoahelp']['uid']=$arr['userID'];
					$_SESSION['etoahelp']['username']=$arr['username'];					
					$_SESSION['etoahelp']['email']=$arr['email'];				
				}
				else
				{
					$error = "Keine Berechtigung!";
				}
			}		
			else
			{
				$error = "User nicht vorhanden oder Passwort falsch!";
			}
		}
		else
		{
			$error = "User nicht vorhanden oder Passwort falsch!";
		}
	}

	if (!$auth)
	{
		header("WWW-Authenticate: Basic realm=\"EtoA.ch Hilfe\"");
		header("HTTP/1.0 401 Unauthorized"); 
	}
	
	if ($auth)
	{				
		if (isset($_SERVER["HTTP_REFERER"]))
			forward($_SERVER["HTTP_REFERER"]);	
		else
			forwardInternal(SITE_URL);	
	}
	else
	{
		echo "<h1>Fehler:</h1><p>".$error."</p><p>Bitte logge dich mit deinem EtoA Forum-Account ein!</p><p>";
		echo "<input type=\"button\" value=\"Neu einloggen\" onclick=\"document.location='?page=$page'\" />";	
		echo "<input type=\"button\" value=\"Account erstellen\" onclick=\"document.location='http://forum.etoa.ch/index.php?page=Register'\" />";	
		echo "<input type=\"button\" value=\"Zurück\" onclick=\"document.location='".SITE_URL."'\" /></p>";	
	}
	$ob = ob_get_clean();
	if ($ob != "")
		$smarty->assign("content",$ob);
	
	$smarty->assign("content_for_layout",$smarty->fetch("views/help/default.html"));
	
	// Render
	$smarty->display('layouts/help.html');	
?>