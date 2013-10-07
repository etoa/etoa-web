<?PHP

	// Session-Cookie setzen
	ini_set('arg_separator.output',  '&amp;');
	session_start();

	// Zufallsgenerator initialisieren
	mt_srand(time());

	// Konfiguration laden
	include("conf.inc.php");
	include("functions.php");

	// Mit der DB verbinden und Config-Werte laden
	dbconnect();
	$conf = get_all_config();

	// Zufallsgenerator initialisieren
	mt_srand(time());

	// Spiel-Runden
	$gameround = get_gamerounds();


	$rounds[0]['round_name']="Classic";
	$rounds[0]['round_url']="http://88.198.37.135/classic/index.php";
	$rounds[1]['round_name']="Runde 1";
	$rounds[1]['round_url']="http://88.198.37.135/round1/login.php";

	if ($_GET['page']!="" && eregi("^[a-z\_]+$",$_GET['page'])  && strlen($_GET['page'])<=50)
		$page=$_GET['page'];
	else
		$page="news";

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><? echo $conf['game_name']['v'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="style.css">
<script src="scripts.js" type="text/javascript"></script>

</head>

<body class="body" onload="scrolltextInit(); ShowState_shown_<?PHP echo $page;?>();">

<div class="Table_01">
	<div class="Header_">
		<img name="Header" id="Header" src="images/Header_<?PHP echo $page;?>.gif" width="263" height="45" />
	</div>
	<div class="Scr-up_">
		<img name="Scr_up" id="Scr_up" src="images/Scr_up.gif" width="35" height="36" border="0" usemap="#Scr_up_map" />
	</div>
	<div class="Line1_">
		<img id="Line1" src="images/Line1.gif" width="390" height="20" />
	</div>
	<div class="Line2_">
		<img id="Line2" src="images/Line2.gif" width="14" height="385" />
	</div>

	<div class="Line1009_">
		<img name="Line1009" id="Line1009" src="images/Line1-09.gif" width="8" height="410" />
	</div>
	<div class="menue1_">
		<img name="menue1" id="menue1" src="images/menue1.gif" width="152" height="20" border="0" usemap="#menue1_Map" />
	</div>
	<div class="show-news_">
		<img name="show_news" id="show_news" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="show-story_">
		<img name="show_story" id="show_story" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue2_">
		<img name="menue2" id="menue2" src="images/menue2.gif" width="152" height="20" border="0" usemap="#menue2_Map" />
	</div>
	<div class="show-regeln_">
		<img name="show_regeln" id="show_regeln" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue3_">
		<img name="menue3" id="menue3" src="images/menue3.gif" width="152" height="20" border="0" usemap="#menue3_Map" />
	</div>
	<div class="show-registrieren_">
		<img name="show_registrieren" id="show_registrieren" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue4_">
		<img name="menue4" id="menue4" src="images/menue4.gif" width="152" height="20" border="0" usemap="#menue4_Map" />
	</div>
	<div class="show-password_">
		<img name="show_password" id="show_password" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue5_">
		<img name="menue5" id="menue5" src="images/menue5.gif" width="152" height="20" border="0" usemap="#menue5_Map" />
	</div>
	<div class="show-rangliste_">
		<img name="show_rangliste" id="show_rangliste" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue6_">
		<img name="menue6" id="menue6" src="images/menue6.gif" width="152" height="20" border="0" usemap="#menue6_Map" />
	</div>
	<div class="show-pranger_">
		<img name="show_pranger" id="show_pranger" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue7_">
		<img name="menue7" id="menue7" src="images/menue7.gif" width="152" height="20" border="0" usemap="#menue7_Map" />
	</div>
	<div class="show-banner_">
		<img name="show_banner" id="show_banner" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue8_">
		<img name="menue8" id="menue8" src="images/menue8.gif" width="152" height="20" border="0" usemap="#menue8_Map" />
	</div>
	<div class="menue99_">
		<img name="menue99" id="menue99" src="images/menue99.gif" width="152" height="20" border="0" usemap="#menue99_Map" />
	</div>
	<div class="show-forum_">
		<img name="show_forum" id="show_forum" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="show-chat_">
		<img name="show_chat" id="show_chat" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue9_">
		<img name="menue9" id="menue9" src="images/menue9.gif" width="152" height="20" border="0" usemap="#menue9_Map" />
	</div>
	<div class="show-wikipedia_">
		<img name="show_wikipedia" id="show_wikipedia" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue10_">
		<img name="menue10" id="menue10" src="images/menue10.gif" width="152" height="20" border="0" usemap="#menue10_Map" />
	</div>
	<div class="show-teamspeak_">
		<img name="show_teamspeak" id="show_teamspeak" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue11_">
		<img name="menue11" id="menue11" src="images/menue11.gif" width="152" height="20" border="0" usemap="#menue11_Map" />
	</div>
	<div class="Scr-down_">
		<img src="images/Scr_down.gif" name="Scr_down" width="35" height="36" border="0" usemap="#Scr_down_Map" id="Scr_down" />
	</div>
	<div class="show-kontakt_">
		<img name="show_kontakt" id="show_kontakt" src="images/blank.gif" width="25" height="20" />
  </div>
	<div class="menue12_">
		<img name="menue12" id="menue12" src="images/menue12.gif" width="152" height="20" border="0" usemap="#menue12_Map" />
	</div>
	<div class="show-disclaimer_">
		<img name="show_disclaimer" id="show_disclaimer" src="images/blank.gif" width="25" height="20" />
	</div>
	<div class="menue13_">
		<img name="menue13" id="menue13" src="images/menue13.gif" width="152" height="20" border="0" usemap="#menue13_Map" />
	</div>
	<div class="show-impressum_">
		<img name="show_impressum" id="show_impressum" src="images/blank.gif" width="25" height="25" />
	</div>
	<div class="menue14_">
		<img name="menue14" id="menue14" src="images/menue14.gif" width="152" height="25" border="0" usemap="#menue14_Map" />
	</div>
	<div class="captcha_">
		<img id="captcha" src="images/captcha.png" width="100" height="33" />
	</div>
	<div class="dd-runde_">
		<img name="dd_runde" id="dd_runde" src="images/dd_runde.gif" width="27" height="29" border="0" usemap="#dd_runde_map" />
	</div>
	<div class="btn-login_">
		<img name="btn_login" id="loginsubmit" src="images/btn_login.gif" width="104" height="19" border="0" usemap="#btn_login_map" />
	</div>

	<div class="login">
		<FORM id=loginform style="MARGIN-BOTTOM: 0px"
      					   onsubmit="changeAction('login');" action=? method=post>

			<input name=login_nick class="txtname_" id=loginname size=11 maxlength=250 />
        	<INPUT name=login_pw type=password class="txtpasswort_" id=loginpw size=11 maxLength=250>
        	<INPUT name=login_round class="txtbildcode_" size=11 maxLength=250>
			<INPUT name=login_verification1 class="txt-runde_" size=11 maxLength=250 disabled>
			<INPUT class="btn-login_" id="btn_login" type=submit value="" name=name=login_submit>

	    </FORM>
  	</div>

	<!-- Dropdown -->
	<div id=uniDropDown onmouseover="uniDropDown(true);return true;" 	onmouseout="uniDropDown(false);return true;" >
		<?PHP
			foreach ($rounds as $arr)
			{
				echo "<a onmousedown=\"changeText('".$arr['round_name']."')\">".$arr['round_name']."</a>";
			}
		?>
	</div>

</div>

<!-- Content -->
<div id="divScrollTextCont">
	<div id="divText">
		<?PHP
			if (!@include("content/".$page.".php"))
				echo "<h1>Fehler</h1>Die Seite <b>".$page."</b> existiert nicht!<br><br><a href=\"?\">Zur&uuml;ck</a>";
		?>
	</div>
</div>

<map name="Scr_up_Map">
<area shape="poly" coords="7,25, 31,25, 18,6" href="#"
	onmouseover="changeImages('Scr_up', 'images/Scr_up-detail_scroll_up_ove.gif'); return true;"
	onmouseout="changeImages('Scr_up', 'images/Scr_up.gif');noScroll();noScroll(); return true;"
	onmousedown="changeImages('Scr_up', 'images/Scr_up-detail_scroll_up_dow.gif'); scroll(-15); return true;"
	onmouseup="changeImages('Scr_up', 'images/Scr_up-detail_scroll_up_ove.gif');noScroll(); return true;" />
</map>

<map name="menue1_Map" id="menue1_Map">
<area shape="rect" coords="0,3,141,20" href="?page=news"
	onmouseover="changeImages('menue1', 'images/menue1-imap_news_over.gif', 'menue2', 'images/menue2-imap_news_over.gif'); return true;"
	onmouseout="changeImages('menue1', 'images/menue1.gif', 'menue2', 'images/menue2.gif'); return true;"
	onclick="ShowState_shown_news();" />
</map>

<map name="menue2_Map" id="menue2_Map">
<area shape="rect" coords="0,3,141,20" href="?page=story"
	onmouseover="changeImages('menue1', 'images/menue1.gif', 'menue2', 'images/menue2-imap_story_over.gif', 'menue3', 'images/menue3-imap_story_over.gif'); return true;"
	onmouseout="changeImages('menue1', 'images/menue1.gif', 'menue2', 'images/menue2.gif', 'menue3', 'images/menue3.gif'); return true;"
	onclick="ShowState_shown_story();" />
</map>

<map name="menue3_Map" id="menue3_Map">
<area shape="rect" coords="0,3,141,20" href="?page=regeln"
	onmouseover="changeImages('menue2', 'images/menue2.gif', 'menue3', 'images/menue3-imap_regeln_over.gif', 'menue4', 'images/menue4-imap_regeln_over.gif'); return true;"
	onmouseout="changeImages('menue2', 'images/menue2.gif', 'menue3', 'images/menue3.gif', 'menue4', 'images/menue4.gif'); return true;"
	onclick="ShowState_shown_regeln();" />
</map>

<map name="menue4_Map" id="menue4_Map">
<area shape="rect" coords="0,3,141,20" href="?page=register"
	onmouseover="changeImages('menue3', 'images/menue3.gif', 'menue4', 'images/menue4-imap_registrieren_ov.gif', 'menue5', 'images/menue5-imap_registrieren_ov.gif'); return true;"
	onmouseout="changeImages('menue3', 'images/menue3.gif', 'menue4', 'images/menue4.gif', 'menue5', 'images/menue5.gif'); return true;"
	onclick="ShowState_shown_registrieren();" />
</map>

<map name="menue5_Map" id="menue5_Map">
<area shape="rect" coords="0,3,141,20" href="?page=pwrequest"
	onmouseover="changeImages('menue4', 'images/menue4.gif', 'menue5', 'images/menue5-imap_password_over.gif', 'menue6', 'images/menue6-imap_password_over.gif'); return true;"
	onmouseout="changeImages('menue4', 'images/menue4.gif', 'menue5', 'images/menue5.gif', 'menue6', 'images/menue6.gif');return true;"
	onclick="ShowState_shown_password()"; />
</map>

<map name="menue6_Map" id="menue6_Map">
<area shape="rect" coords="0,3,141,20" href="?page=rangliste"
	onmouseover="changeImages('menue5', 'images/menue5.gif', 'menue6', 'images/menue6-imap_rangliste_over.gif', 'menue7', 'images/menue7-imap_rangliste_over.gif'); return true;"
	onmouseout="changeImages('menue5', 'images/menue5.gif', 'menue6', 'images/menue6.gif', 'menue7', 'images/menue7.gif'); return true;"
	onclick="ShowState_shown_rangliste();" />
</map>

<map name="menue7_Map" id="menue7_Map">
<area shape="rect" coords="0,3,141,20" href="?page=pranger"
	onmouseover="changeImages('menue6', 'images/menue6.gif', 'menue7', 'images/menue7-imap_pranger_over.gif', 'menue8', 'images/menue8-imap_pranger_over.gif'); return true;"
	onmouseout="changeImages('menue6', 'images/menue6.gif', 'menue7', 'images/menue7.gif', 'menue8', 'images/menue8.gif'); return true;"
	onclick="ShowState_shown_pranger();" />
</map>

<map name="menue8_Map" id="menue8_Map">
<area shape="rect" coords="0,3,141,20" href="?page=banner"
	onmouseover="changeImages('menue7', 'images/menue7.gif', 'menue8', 'images/menue8-imap_banner_over.gif', 'menue99', 'images/menue99-imap_banner_over.gif'); return true;"
	onmouseout="changeImages('menue7', 'images/menue7.gif', 'menue8', 'images/menue8.gif', 'menue99', 'images/menue99.gif'); return true;"
	onclick="ShowState_shown_banner();"/>
</map>

<map name="menue9_Map" id="menue9_Map">
<area shape="rect" coords="0,3,141,20" href="?page=chat"
	onmouseover="changeImages('menue99', 'images/menue99.gif', 'menue9', 'images/menue9-imap_chat_over.gif', 'menue10', 'images/menue10-imap_chat_over.gif'); return true;"
	onmouseout="changeImages('menue99', 'images/menue99.gif', 'menue9', 'images/menue9.gif', 'menue10', 'images/menue10.gif'); return true;"
	onclick="ShowState_shown_chat();" />
</map>

<map name="menue10_Map" id="menue10_Map">
<area shape="rect" coords="0,3,141,20" href="http://www.etoa.ch/wiki" target="_blank"
	onmouseover="changeImages('menue9','images/menue9.gif', 'menue10', 'images/menue10-imap_wikipedia_over.gif', 'menue11', 'images/menue11-imap_wikipedia_over.gif'); return true;"
	onmouseout="changeImages('menue9', 'images/menue9.gif', 'menue10', 'images/menue10.gif', 'menue11', 'images/menue11.gif'); return true;"
	onclick="ShowState_shown_wikipedia();" />
</map>

<map name="menue11_Map" id="menue11_Map">
<area shape="rect" coords="0,3,141,20" href="teamspeak://85.214.47.55:5017"
	onmouseover="changeImages('menue10', 'images/menue10.gif', 'menue11', 'images/menue11-imap_teamspeak_over.gif', 'menue12', 'images/menue12-imap_teamspeak_over.gif'); return true;"
	onmouseout="changeImages('menue10', 'images/menue10.gif', 'menue11', 'images/menue11.gif', 'menue12', 'images/menue12.gif'); return true;" />
</map>

<map name="menue12_Map" id="menue12_Map">
<area shape="rect" coords="0,3,141,20" href="?page=kontakt"
	onmouseover="changeImages('menue11', 'images/menue11.gif', 'menue12', 'images/menue12-imap_kontakt_over.gif', 'menue13', 'images/menue13-imap_kontakt_over.gif'); return true;"
	onmouseout="changeImages('menue11', 'images/menue11.gif', 'menue12', 'images/menue12.gif', 'menue13', 'images/menue13.gif'); return true;"
	onclick="ShowState_shown_kontakt();" />
</map>

<map name="menue13_Map" id="menue13_Map">
<area shape="rect" coords="0,3,141,20" href="?page=disclaimer"
	onmouseover="changeImages('menue12', 'images/menue12.gif', 'menue13', 'images/menue13-imap_disclaimer_ove.gif', 'menue14', 'images/menue14-imap_disclaimer_ove.gif'); return true;"
	onmouseout="changeImages('menue12', 'images/menue12.gif', 'menue13', 'images/menue13.gif', 'menue14', 'images/menue14.gif'); return true;"
	onclick="ShowState_shown_disclaimer();" />
</map>

<map name="menue14_Map" id="menue14_Map">
<area shape="rect" coords="0,3,141,22" href="?page=impressum"
	onmouseover="changeImages('menue13', 'images/menue13.gif', 'menue14', 'images/menue14-imap_impressum_over.gif'); return true;"
	onmouseout="changeImages('menue13', 'images/menue13.gif', 'menue14', 'images/menue14.gif'); return true;"
	onclick="ShowState_shown_impressum();" />
</map>

<map name="menue99_Map" id="menue99_Map">
<area shape="rect" coords="0,3,141,20" href="http://www.etoa.ch/forum" target="_blank"
	onmouseover="changeImages('menue8', 'images/menue8.gif', 'menue99', 'images/menue99-imap_forum_over.gif', 'menue9', 'images/menue9-imap_banner_over.gif'); return true;"
	onmouseout="changeImages('menue9', 'images/menue9.gif', 'menue99', 'images/menue99.gif', 'menue9', 'images/menue9.gif'); return true;"
	onclick="ShowState_shown_banner();" />
</map>

<map name="Scr_down_Map">
<area shape="poly" coords="7,12, 29,12, 18,32" href="#"
	onmouseover="changeImages('Scr_down', 'images/Scr_down-detail_scroll_down.gif'); return true;"
	onmouseout="changeImages('Scr_down', 'images/Scr_down.gif'); noScroll(); return true;"
	onmousedown="changeImages('Scr_down', 'images/Scr_down-detail_scroll_-110.gif'); scroll(15); return true;"
	onmouseup="changeImages('Scr_down', 'images/Scr_down-detail_scroll_down.gif'); noScroll(); return true;" />
</map>

<map name="dd_runde_Map">
	<area shape="poly" coords="2,7, 22,7, 13,24"
		onmouseover="changeImages('dd_runde', 'images/dd_runde-dd_login_over.gif');UniDropDown(false);  return true;"
		onmouseout="changeImages('dd_runde', 'images/dd_runde.gif'); return true;"
		onmousedown="changeImages('dd_runde', 'images/dd_runde-dd_login_down.gif'); UniDropDown(true); return true;"
		onmouseup="changeImages('dd_runde', 'images/dd_runde-dd_login_over.gif'); return true;" />
</map>

<map name="btn_login_Map">
	<area shape="rect" coords="0,0,103,18"
		onmouseover="changeImages('btn_login', 'images/btn_login-over.gif'); return true;"
		onmouseout="changeImages('btn_login', 'images/btn_login.gif'); return true;"
		onmousedown="changeImages('btn_login', 'images/btn_login-down.gif'); return true;"
		onmouseup="changeImages('btn_login', 'images/btn_login-over.gif'); return true;" />
</map>

</body>
</html>