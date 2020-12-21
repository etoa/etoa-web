<?PHP

	//////////////////////////////////////////////////
	// The Andromeda-Project-Browsergame						//
	// Ein Massive-Multiplayer-Online-Spiel					//
	// Programmiert von Nicolas Perrenoud						//
	// www.nicu.ch | mail@nicu.ch										//
	// als Maturaarbeit '04 am Gymnasium Oberaargau	//
	// ---------------------------------------------//
	// Datei: functions.php													//
	// Topic: Funktionen						 								//
	// Version: 0.2																	//
	// Letzte Änderung: 03.05.2005									//
	//////////////////////////////////////////////////

	//
	// Mit Datenbank verbinden
	//

	function dbconnect($utf8=1)
	{
		global $db_access;
		global $db_handle;
		if (!$db_handle = @mysql_connect($db_access["server"],$db_access["user"],$db_access["pw"]))
		{
			echo "</head><body>";
			print_fs_error_msg("Zum Datenbankserver auf <b>".$db_access['server']."</b> kann keine Verbindung hergestellt werden! Bitte schaue später nochmals vorbei.<br/><br/><a href=\"http://forum.etoa.ch\">Zum Forum</a> | <a href=\"mailto:mail@etoa.ch\">Mail an die Spielleitung</a>","MySQL-Verbindungsproblem");
		}
		if (!@mysql_select_db($db_access["db"]))
		{
			echo "</head><body>";
			print_fs_error_msg("Auf die Datenbank <b>".$db_access[db]."</b> auf <b>".$db_access[server]."</b> kann nicht zugegriffen werden! Bitte schaue später nochmals vorbei.<br/><br/><a href=\"http://forum.etoa.ch\">Zum Forum</a> | <a href=\"mailto:mail@etoa.ch\">Mail an die Spielleitung</a>","MySQL-Verbindungsproblem");
		}
		if ($utf8==1)
		dbquery("SET NAMES 'utf8';");

	}

	//
	// Datenbankverbindung trennen
	//

	function dbclose()
	{
		global $db_handle;
		global $res;
		if (isset($res))
		{
			@mysql_free_result($res);
		}
		@mysql_close($db_handle);
	}

	//
	// Datenbankquery ausführen
	//

	function dbquery($string, $fehler=1)
	{
		if ($result=mysql_query($string))
		{
			return $result;
		}
		else
		{
			if ($fehler==1)
			{
				echo "<p><b>Datenbank-Fehler:</b> ".mysql_error()."!<br><b>Query:</b> $string</p>";
			}
		}
	}
	//
	// Gesamte Config-Tabelle lesen und Werte in Array speichern
	//

	function baseUrl()
{
	$str = substr($_SERVER['SCRIPT_FILENAME'],strlen($_SERVER['DOCUMENT_ROOT']));
	return substr($str,0,strrpos($str,"/")+1);
}


	function get_all_config()
	{
		global $db_table;
		$conf = array();
		$res = mysql_query("SELECT config_name,config_value,config_param1,config_param2 FROM ".$db_table['config'].";");
		while ($arr = mysql_fetch_array($res))
		{
			$conf[$arr['config_name']]['v'] = $arr['config_value'];
			$conf[$arr['config_name']]['p1'] = $arr['config_param1'];
			$conf[$arr['config_name']]['p2'] = $arr['config_param2'];
		}
		return $conf;
	}

	//
	// Zahlen formatieren
	//

	function nf($number)	// Number format
	{
		return number_format($number,0,".","'");
	}

	//
	// Zeit formatieren
	//

	function tf($ts)	// Time format
	{
		$t = floor($ts / 3600 / 24);
		$h = floor(($ts-($t*24*3600)) / 3600);
		$m = floor(($ts-($t*24*3600)-($h*3600))/60);
		$s = floor(($ts-($t*24*3600)-($h*3600)-($m*60)));

		$str = "";
		if ($t > 0) $str.=$t."d ";
		if ($h > 0) $str.=$h."h ";
		if ($m > 0) $str.=$m."m ";
		if ($s > 0) $str.=$s."s ";
		return $str;
	}

	function tfs($ts)	// Time format
	{
		if ($ts < 60)
			return "vor ".$ts." s";
		if ($ts < 3600)
			return "vor ".ceil($ts/60)." m";
		$tm = time();
		$mn = mktime(0,0,0,date("m",$tm),date("d",$tm),date("Y",$tm));
		if ($ts < $tm-$mn)
			return "vor ".ceil($ts/3600)." h";
		if ($ts-86400 < $tm-$mn)
			return "gestern";
		if ($ts-(86400*2) < $tm-$mn)
			return "vor 2 Tagen";
		if ($ts-(86400*3) < $tm-$mn)
			return "vor 3 Tagen";
		return date("d.m.y",$tm-$ts);
	}

	//
	// Datum formatieren
	//
	function df($date)
	{
		if (date("dmY") == date("dmY",$date))
			$string = "Heute, ".date("H:i",$date);
		else
			$string = date("d.m.y, H:i",$date);
		return $string;
	}

	function format_link($string)
	{
		$string = preg_replace("/([ \n])http:\/\/([^ ,\n]*)/", "\\1[url]http://\\2[/url]", $string);
		$string = preg_replace("/([ \n])ftp:\/\/([^ ,\n]*)/", "\\1[url]ftp://\\2[/url]", $string);
		$string = preg_replace("/([ \n])www\\.([^ ,\n]*)/", "\\1[url]http://www.\\2[/url]", $string);
		$string = preg_replace("/^http:\/\/([^ ,\n]*)/", "[url]http://\\1[/url]", $string);
		$string = preg_replace("/^ftp:\/\/([^ ,\n]*)/", "[url]ftp://\\1[/url]", $string);
		$string = preg_replace("/^www\\.([^ ,\n]*)/", "[url]http://www.\\1[/url]", $string);
	 	$string = preg_replace('/\[url\]www.([^\[]*)\[\/url\]/', '<a href="http://www.\1" >\1</a>', $string);
		$string = preg_replace('/\[url\]([^\[]*)\[\/url\]/', '<a href="\1" >\1</a>', $string);
		$string = preg_replace('/\[mailurl\]([^\[]*)\[\/mailurl\]/', '<a href="\1">Link</a>', $string);
		return $string;
	}

	/**
	*	BB-Code Wrapper
	*
	* @param $string Text to wrap BB-Codes into HTML
	* @return Wrapped text
	*
	* @author MrCage | Nicolas Perrenoud
	*
	* @last editing: Demora | Selina Tanner 04.06.2007
	*/

	function text2html($string)
	{
		$string = str_replace('&', '&amp;', $string);

		$string = str_replace("  ", "&nbsp;&nbsp;", $string);

		$string = str_replace("\"", "&quot;", $string);
		$string = str_replace("<", "&lt;", $string);
		$string = str_replace(">", "&gt;", $string);

		$string =  preg_replace("((\r\n))", trim('<br/>'), $string);
		$string =  preg_replace("((\n))", trim('<br/>'), $string);
		$string =  preg_replace("((\r)+)", trim('<br/>'), $string);

		$string = str_replace('[b]', '<b>', $string);
		$string = str_replace('[/b]', '</b>', $string);
		$string = str_replace('[B]', '<b>', $string);
		$string = str_replace('[/B]', '</b>', $string);
		$string = str_replace('[i]', '<i>', $string);
		$string = str_replace('[/i]', '</i>', $string);
		$string = str_replace('[I]', '<i>', $string);
		$string = str_replace('[/I]', '</i>', $string);
		$string = str_replace('[u]', '<u>', $string);
		$string = str_replace('[/u]', '</u>', $string);
		$string = str_replace('[U]', '<u>', $string);
		$string = str_replace('[/U]', '</u>', $string);
		$string = str_replace('[c]', '<div style="text-align:center;">', $string);
		$string = str_replace('[/c]', '</div>', $string);
		$string = str_replace('[C]', '<div style="text-align:center;">', $string);
		$string = str_replace('[/C]', '</div>', $string);
		$string = str_replace('[bc]', '<blockquote class="blockquotecode"><code>', $string);
		$string = str_replace('[/bc]', '</code></blockquote>', $string);
		$string = str_replace('[BC]', '<blockquote class="blockquotecode"><code>', $string);
		$string = str_replace('[/BC]', '</code></blockquote>', $string);


		$string = str_replace('[h1]', '<h1>', $string);
		$string = str_replace('[/h1]', '</h1>', $string);
		$string = str_replace('[H1]', '<h1>', $string);
		$string = str_replace('[/H1]', '</h1>', $string);
		$string = str_replace('[h2]', '<h2>', $string);
		$string = str_replace('[/h2]', '</h2>', $string);
		$string = str_replace('[H2]', '<h2>', $string);
		$string = str_replace('[/H2]', '</h2>', $string);
		$string = str_replace('[h3]', '<h3>', $string);
		$string = str_replace('[/h3]', '</h3>', $string);
		$string = str_replace('[H3]', '<h3>', $string);
		$string = str_replace('[/H3]', '</h3>', $string);

		$string = str_replace('[center]', '<div style="text-align:center">', $string);
		$string = str_replace('[/center]', '</div>', $string);		$string = str_replace('[align=center]', '<div style="text-align:center">', $string);		$string = str_replace('[/align]', '</div>', $string);
		$string = str_replace('[right]', '<div style="text-align:right">', $string);
		$string = str_replace('[/right]', '</div>', $string);
		$string = str_replace('[headline]', '<div style="text-align:center"><b>', $string);
		$string = str_replace('[/headline]', '</b></div>',$string);

		$string = str_replace('[CENTER]', '<div style="text-align:center">', $string);
		$string = str_replace('[/CENTER]', '</div>', $string);
		$string = str_replace('[RIGHT]', '<div style="text-align:right">', $string);
		$string = str_replace('[/RIGHT]', '</div>', $string);
		$string = str_replace('[HEADLINE]', '<div style="text-align:center"><b>', $string);
		$string = str_replace('[/HEADLINE]', '</b></div>',$string);

		$string = str_replace('[*]', '<li>', $string);
		$string = str_replace('[/*]', '</li>', $string);

		$string = preg_replace('/\[list=1]([^\[]*)\[\/list\]/', '<ol style="list-style-type:decimal">\1</ol>', $string);
		$string = preg_replace('/\[list=a]([^\[]*)\[\/list\]/', '<ol style="list-style-type:lower-latin">\1</ol>', $string);
		$string = preg_replace('/\[list=a]([^\[]*)\[\/list\]/', '<ol style="list-style-type:lower-latin">\1</ol>', $string);
		$string = preg_replace('/\[list=I]([^\[]*)\[\/list\]/', '<ol style="list-style-type:upper-roman">\1</ol>', $string);
		$string = preg_replace('/\[list=i]([^\[]*)\[\/list\]/', '<ol style="list-style-type:upper-roman">\1</ol>', $string);

		$string = str_replace('[list]', '<ul>', $string);
		$string = str_replace('[/list]', '</ul>', $string);
		$string = str_replace('[nlist]', '<ol style="list-style-type:decimal">', $string);
		$string = str_replace('[/nlist]', '</ol>', $string);
		$string = str_replace('[alist]', '<ol style="list-style-type:lower-latin">', $string);
		$string = str_replace('[/alist]', '</ol>', $string);
		$string = str_replace('[rlist]', '<ol style="list-style-type:upper-roman">', $string);
		$string = str_replace('[/rlist]', '</ol>', $string);

		$string = str_replace('[LIST]', '<ul>', $string);
		$string = str_replace('[/LIST]', '</ul>', $string);
		$string = str_replace('[NLIST]', '<ol style="list-style-type:decimal">', $string);
		$string = str_replace('[/NLIST]', '</ol>', $string);
		$string = str_replace('[ALIST]', '<ol style="list-style-type:lower-latin">', $string);
		$string = str_replace('[/ALIST]', '</ol>', $string);
		$string = str_replace('[RLIST]', '<ol style="list-style-type:upper-roman">', $string);
		$string = str_replace('[/RLIST]', '</ol>', $string);

		$string = str_replace('[element]', '<li>', $string);
		$string = str_replace('[/element]', '</li>', $string);
		$string = str_replace('[ELEMENT]', '<li>', $string);
		$string = str_replace('[/ELEMENT]', '</li>', $string);

		$string = str_replace('[line]', '<hr class="line" />', $string);
		$string = str_replace('[LINE]', '<hr class="line" />', $string);


		$string = preg_replace('/\[codebox ([^\[]*) ([^\[]*)\]/', '<textarea readonly=\"readonly\" rows=\"\1\" cols=\"\2\">', $string);

		$string = str_replace('[codebox]', '<textarea readonly=\"readonly\" rows=\"3\" cols=\"60\">', $string);
		$string = str_replace('[/codebox]', '</textarea>', $string);


		$string = preg_replace('/\[quote]([^\[]*)\[\/quote\]/i', '<fieldset class="quote"><legend class="quote"><b>Zitat</b></legend>\1</fieldset>', $string);
		$string = preg_replace('/\[quote ([^\[]*)\]([^\[]*)\[\/quote\]/i', '<fieldset class="quote"><legend class="quote"><b>Zitat von:</b> \1</legend>\2</fieldset>', $string);
		$string = preg_replace('/\[quote=([^\[]*)\]([^\[]*)\[\/quote\]/i', '<fieldset class="quote"><legend class="quote"><b>Zitat von:</b> \1</legend>\2</fieldset>', $string);
		$string = preg_replace('/\[img\]([^\[]*)\[\/img\]/i', '<img src="\1" alt="\1" border="0" />', $string);
		$string = preg_replace('/\[img ([0-9]*) ([0-9]*)\]([^\[]*)\[\/img]/i', '<img src="\3" alt="\3" width="\1" height="\2" border="0" />', $string);
		$string = preg_replace('/\[img ([0-9]*)\]([^\[]*)\[\/img]/i', '<img src="\2" alt="\2" width="\1" border="0" />', $string);
		$string = preg_replace('/\[flag ([^\[]*)\]/', '<img src="images/flags/i'.strtolower('\1').'.gif" border="0" alt="Flagge \1" class=\"flag\" />', $string);
		$string = preg_replace('/\[thumb ([0-9]*)\]([^\[]*)\[\/thumb]/i', '<a href="\2"><img src="\2" alt="\2" width="\1" border="0" /></a>', $string);

		$string = preg_replace("/^http:\/\/([^ ,\n]*)/", "[url]http://\\1[/url]", $string);
		$string = preg_replace("/^ftp:\/\/([^ ,\n]*)/", "[url]ftp://\\1[/url]", $string);
		$string = preg_replace("/^www\\.([^ ,\n]*)/", "[url]http://www.\\1[/url]", $string);

		$string = preg_replace('/\[url=\'([^\[]*)\'\]([^\[]*)\[\/url\]/i', '<a href="\1">\2</a>', $string);
		$string = preg_replace('/\[url=([^\[]*)\]([^\[]*)\[\/url\]/i', '<a href="\1">\2</a>', $string);
		$string = preg_replace('/\[url ([^\[]*)\]([^\[]*)\[\/url\]/i', '<a href="\1">\2</a>', $string);
	 	$string = preg_replace('/\[url\]www.([^\[]*)\[\/url\]/i', '<a href="http://www.\1">\1</a>', $string);
		$string = preg_replace('/\[url\]([^\[]*)\[\/url\]/i', '<a href="\1">\1</a>', $string);

		$string = preg_replace('/\[mailurl=([^\[]*)\]([^\[]*)\[\/mailurl\]/i', '<a href="mailto:\1">\2</a>', $string);
		$string = preg_replace('/\[mailurl ([^\[]*)\]([^\[]*)\[\/mailurl\]/i', '<a href="mailto:\1">\2</a>', $string);
		$string = preg_replace('/\[mailurl\]([^\[]*)\[\/mailurl\]/i', '<a href="mailto:\1">\1</a>', $string);
		$string = preg_replace('/\[email=([^\[]*)\]([^\[]*)\[\/email\]/i', '<a href="mailto:\1">\2</a>', $string);
		$string = preg_replace('/\[email ([^\[]*)\]([^\[]*)\[\/email\]/i', '<a href="mailto:\1">\2</a>', $string);
		$string = preg_replace('/\[email\]([^\[]*)\[\/email\]/i', '<a href="mailto:\1">\1</a>', $string);

		$string = preg_replace('/== ([^\[]*) ==/', '<h3>\1</h3>', $string);
		$string = preg_replace('/= ([^\[]*) =/', '<h2>\1</h2>', $string);

		$string = str_replace('[table]', '<table class="bbtable">', $string);
    $string = str_replace('[/table]', '</table>', $string);
    $string = str_replace('[td]', '<td>', $string);
    $string = str_replace('[/td]', '</td>', $string);
    $string = str_replace('[th]', '<th>', $string);
    $string = str_replace('[/th]', '</th>', $string);
    $string = str_replace('[tr]', '<tr>', $string);
    $string = str_replace('[/tr]', '</tr>', $string);

    $string = str_replace('[TABLE]', '<table>', $string);
    $string = str_replace('[/TABLE]', '</table>', $string);
    $string = str_replace('[TD]', '<td>', $string);
    $string = str_replace('[/TD]', '</td>', $string);
    $string = str_replace('[TH]', '<th>', $string);
    $string = str_replace('[/TH]', '</th>', $string);
    $string = str_replace('[TR]', '<tr>', $string);
    $string = str_replace('[/TR]', '</tr>', $string);

		$string = preg_replace('/\[font ([^\[]*)\]/i', '<span style=\"font-family:\1">', $string);
		$string = preg_replace('/\[color ([^\[]*)\]/i', '<span style=\"color:\1">', $string);
		$string = preg_replace('/\[size ([^\[]*)\]/i', '<span style=\"font-size:\1pt">', $string);
		$string = preg_replace('/\[font=([^\[]*)\]/i', '<span style=\"font-family:\1">', $string);
		$string = preg_replace('/\[color=([^\[]*)\]/i', '<span style=\"color:\1">', $string);
		$string = preg_replace('/\[size=([^\[]*)\]/i', '<span style=\"font-size:\1pt">', $string);
		$string = str_replace('[/font]', '</span>', $string);
		$string = str_replace('[/FONT]', '</span>', $string);
		$string = str_replace('[/color]', '</span>', $string);
		$string = str_replace('[/COLOR]', '</span>', $string);
		$string = str_replace('[/size]', '</span>', $string);
		$string = str_replace('[/SIZE]', '</span>', $string);

 		$string = stripslashes($string);

		//$string=htmlentities($string);


		return $string;
	}

$flaglist['ch-la']="Langenthal";
$flaglist['ch-ag']="Kanton Aargau";
$flaglist['ch-ai']="Kanton Appenzell-Innerrhoden";
$flaglist['ch-ar']="Kanton Appenzell-Ausserrhoden";
$flaglist['ch-be']="Kanton Bern";
$flaglist['ch-bl']="Kanton Basel-Landschaft";
$flaglist['ch-bs']="Kanton Basel-Stadt";
$flaglist['ch-ge']="Kanton Genf";
$flaglist['ch-gr']="Kanton Graub&uuml;nden";
$flaglist['ch-ju']="Kanton Jura";
$flaglist['ch-lu']="Kanton Luzern";
$flaglist['ch-nw']="Kanton Nidwalden";
$flaglist['ch-ow']="Kanton Obwalden";
$flaglist['ch-sh']="Kanton Schaffhausen";
$flaglist['ch-so']="Kanton Solothurn";
$flaglist['ch-sz']="Kanton Schwyz";
$flaglist['ch-tg']="Kanton Thurgau";
$flaglist['ch-ti']="Kanton Tessin";
$flaglist['ch-ur']="Kanton Uri";
$flaglist['ch-vd']="Kanton Waadt";
$flaglist['ch-vs']="Kanton Wallis";
$flaglist['ch-zg']="Kanton Zug";
$flaglist['ch-zh']="Kanton Z&uuml;rich";
$flaglist['ar']="Argentinien";
$flaglist['at']="&Ouml;sterreich";
$flaglist['au']="Australien";
$flaglist['be']="Belgien";
$flaglist['benelux']="Benelux";
$flaglist['bg']="Bulgarien";
$flaglist['br']="Brasilien";
$flaglist['ca']="Kanada";
$flaglist['ch']="Schweiz";
$flaglist['cn']="China";
$flaglist['hr']="Kroatien";
$flaglist['cz']="Tschechische Republik";
$flaglist['de']="Deutschland";
$flaglist['dk']="D&auml;nemark";
$flaglist['ee']="Estland";
$flaglist['eu']="Europa";
$flaglist['fi']="Finnland";
$flaglist['fr']="Frankreich";
$flaglist['gb']="Grossbritanien";
$flaglist['gr']="Griechenland";
$flaglist['il']="Israel";
$flaglist['in']="India";
$flaglist['it']="Italien";
$flaglist['jp']="Japan";
$flaglist['kp']="Korea";
$flaglist['lv']="Lettland";
$flaglist['lu']="Luxemburg";
$flaglist['nl']="Niederlande";
$flaglist['no']="Norwegen";
$flaglist['pl']="Polen";
$flaglist['ru']="Russland";
$flaglist['sk']="Slovakei";
$flaglist['sp']="Spanien";
$flaglist['se']="Schweden";
$flaglist['ty']="T&uuml;rkey";
$flaglist['us']="USA";
$flaglist['vn']="Vietnam";
$flaglist['world']="Welt";

$colorlist['black']="Schwarz";
$colorlist['darkred']="Dunkelrot";
$colorlist['red']="Rot";
$colorlist['orange']="Orange";
$colorlist['brown']="Braun";
$colorlist['yellow']="Gelb";
$colorlist['green']="Gr&uuml;n";
$colorlist['olive']="Olive";
$colorlist['cyan']="Cyan";
$colorlist['blue']="Blau";
$colorlist['darkblue']="Dunkelblau";
$colorlist['indigo']="Indigo";
$colorlist['violet']="Violet";
$colorlist['white']="Weiss";

$sizelist['8']="Klein";
$sizelist['10']="Mittel";
$sizelist['12']="Mittelgross";
$sizelist['14']="Gross";
$sizelist['17']="Ganz gross";


	function send_msg($user_id,$msg_type,$subject,$text)
	{
		global $db_table;
		dbquery("INSERT INTO ".$db_table['messages']." (message_user_from,message_user_to,message_timestamp,message_cat_id,message_subject,message_text) VALUES (0,'$user_id',".time().",$msg_type,'".addslashes($subject)."','".addslashes($text)."');");
	}

	function send_msg_hd($user_id,$msg_type,$subject,$text,$user_from)
	{
		global $db_table;
		dbquery("INSERT INTO ".$db_table['messages']." (message_user_from,message_user_to,message_timestamp,message_cat_id,message_subject,message_text) VALUES ('$user_from','$user_id',".time().",$msg_type,'".addslashes($subject)."','".addslashes($text)."');");
	}

function endpage()
{
	echo "</body></html>";
	exit();
}

	function show_text_tools($textarea_id,$onerow=0)
	{
		global $flaglist;
		global $colorlist;
		global $sizelist;

	  echo "<img src=\"images/buttons/buttonbold.gif\" onclick=\"insertTag(this,'[b]','[/b]','buttonbold.gif','buttonbold_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
	  echo "<img src=\"images/buttons/buttonitalic.gif\" onclick=\"insertTag(this,'[i]','[/i]','buttonitalic.gif','buttonitalic_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
	  echo "<img src=\"images/buttons/buttonunderline.gif\" onclick=\"insertTag(this,'[u]','[/u]','buttonunderline.gif','buttonunderline_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
	  echo "<img src=\"images/buttons/buttoncenter.gif\" onclick=\"insertTag(this,'[center]','[/center]','buttoncenter.gif','buttoncenter_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
	  echo "<img src=\"images/buttons/buttoncode.gif\" onclick=\"insertTag(this,'[c]','[/c]','buttoncode.gif','buttoncode_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
	  echo "<img src=\"images/buttons/buttonquote.gif\" onclick=\"insertTag(this,'[quote autor]','[/quote]','buttonquote.gif','buttonquote_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
	  echo "<img src=\"images/buttons/buttonlink.gif\" onclick=\"insertTag(this,'[url http://]','[/url]','buttonlink.gif','buttonlink_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
	  echo "<img src=\"images/buttons/buttonmail.gif\" onclick=\"insertTag(this,'[mailurl]','[/mailurl]','buttonmail.gif','buttonmail_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
	  echo "<img src=\"images/buttons/buttonimage.gif\" onclick=\"insertTag(this,'[img]','[/img]','buttonimage.gif','buttonimage_.gif','$textarea_id')\" alt=\"[tag]\" /> ";
		if ($onerow==0) echo "<br/>";
	  echo "<select onchange=\"insertTags(this.options[this.selectedIndex].value,'$textarea_id');this.selectedIndex=0;\">";
  	echo "<option value=\"\">Farbe einf&uuml;gen...</option>";
	  foreach ($colorlist as $id => $name)
	  {
  		echo "<option value=\"[color $id][/color]\" style=\"color:$id;\">$name</option>";
	  }
	  echo "</select> ";
	  echo "<select onchange=\"insertTags(this.options[this.selectedIndex].value,'$textarea_id');this.selectedIndex=0;\">";
  	echo "<option value=\"\">Gr&ouml;sse einf&uuml;gen...</option>";
	  foreach ($sizelist as $id => $name)
	  {
  		echo "<option value=\"[size $id][/size]\">$name</option>";
	  }
	  echo "</select>";
	  echo "<select onchange=\"insertTags(this.options[this.selectedIndex].value,'$textarea_id');this.selectedIndex=0;\">";
  	echo "<option value=\"\">Fahne einf&uuml;gen...</option>";
	  foreach ($flaglist as $id => $name)
	  {
  		echo "<option value=\"[flag $id]\">$name</option>";
	  }
	  echo "</select> ";
		echo "<br/>";

	}

	function show_smilies($textarea_id)
	{
		$disp_array = array();
		foreach ($smilielist as $smilie_id=>$smilie_img)
		{
			if (!in_array($smilie_img,$disp_array))
			{
				echo "<a href=\"javascript:;\" onclick=\"insertTags('$smilie_id','$textarea_id');\"><img src=\"".SMILIE_DIR."/".$smilie_img."\" border=\"0\" alt=\"Smilie\" title=\"".$smilie_img."\" /></a> ";
				array_push($disp_array,$smilie_img);
			}
		}
	}

	function show_text($keyword)
	{
		global $db_table;
		$res = dbquery("SELECT * FROM ".$db_table['texts']." WHERE text_keyword='".$keyword."';");
		if (mysql_num_rows($res)>0)
		{
			$arr = mysql_fetch_array($res);
			if ($arr['text_text']!="")
				echo text2html($arr['text_text']);
			else
				echo "<p><i><b>Fehler:</b> Texteintrag fehlt!</i></p>";
		}
		else
		{
			echo "<p><i><b>Fehler:</b> Datensatz fehlt!</i></p>";
		}

	}

	function show_thumb($file,$width=0)
	{
		$folder = substr($file,0,strrpos($file,"/"));
		$filename = substr($file,strrpos($file,"/")+1,strlen($file));
		$string = "";
		if($filename!="")
		{
			if(file_exists($folder."/thumb_".$filename))
				$thumb_path = $folder."/thumb_".$filename;
			elseif(file_exists($folder."/".$filename."_small"))
				$thumb_path = $folder."/".$filename."_small";
			else
			{
				$thumb_path = $file;
				if ($width==0) $width=100;
			}

			$imsize = getimagesize($file);
			$imw = $imsize[0]+30;
			$imh = $imsize[1]+60;
			$string.= "<a href=\"javascript:;\" onclick=\"window.open('img_popup.php?image_url=".$file."','popup','status=no,scrollbars=yes')\">";
			$string.= "<img src=\"$thumb_path\" style=\"margin-right: 8px\" align=\"left\" alt=\"$filename\" title=\"Bild anzeigen\" ";
			if ($width>0) $string.= " width=\"$width\"";
			$string.= " /></a>";
		}
		return $string;
	}

	function boarddateformat($date)
	{
		if (date("dmY") == date("dmY",$date))
			$string = "Heute, ".date("H:i",$date);
		else
			$string = date("d.m.Y, H:i",$date);
		return $string;
	}

	function formatfilesize($file_size)
	{
		if ($file_size<pow(1024,1)) $file_size = $file_size." Byte";
		elseif ($file_size<pow(1024,2)) {$file_size = round($file_size/pow(1024,1),2); $file_size=$file_size." KB";}
		elseif ($file_size<pow(1024,3)) {$file_size = round($file_size/pow(1024,2),2); $file_size=$file_size." MB";}
		elseif ($file_size<pow(1024,4)) {$file_size = round($file_size/pow(1024,2),3); $file_size=$file_size." GB";}
		elseif ($file_size<pow(1024,5)) {$file_size = round($file_size/pow(1024,2),4); $file_size=$file_size." TB";}
		return $file_size;
	}

  function mkdire($dir,$rootdir="",$dirmode=700)
	{
		if (!empty($dir))
		{
			if (!file_exists($rootdir."/".$dir))
		  {
	  		if (!mkdir($rootdir."/".$dir,$dirmode))
					echo "<div class=\"mowemMsgErr\">Fehler: Verzeichnis ".$dir." konnte nicht erstellt werden!</div>";
		 		else
					echo "<div class=\"mowemMsgOk\">Das Verzeichnis $base wurde erfolgreich erstellt!</div>";
			}
			else
				if (!is_dir($rootdir."/".$dir))
		    	echo "<div class=\"mowemMsgErr\">Fehler: ".$dir." existiert bereits und ist kein Verzeichnis!</div>";
		    else
		    	echo "<div class=\"mowemMsgErr\">Fehler: Das Verzeichnis ".$dir." existiert bereits!</div>";
		}
		else
			echo "<div class=\"mowemMsgErr\">Fehler: Kein Verzeichnisname angegeben!</div>";
	}

	function checkEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	function encode_textfield ($string)
	{
		str_replace("\"","&quot;",$string);
		str_replace("&","&amp;",$string);
		str_replace("<","&lt;",$string);
		str_replace(">","&gt;",$string);
		str_replace("´","&acute;",$string);
		str_replace("‘","&lsquo;",$string);
		str_replace("’","&rsquo;",$string);
		str_replace("'","&#39;",$string);

		return $string;
	}

	function decode_textfield ($string)
	{
		str_replace("&quot;","\"",$string);
		str_replace("&amp;","&",$string);
		str_replace("&lt;","<",$string);
		str_replace("&gt;",">",$string);
		str_replace("&acute;","´",$string);
		str_replace("&lsquo;","‘",$string);
		str_replace("&rsquo;","’",$string);
		str_replace("&#39;","'",$string);

		return $string;
	}

	function remove_old_backups()
	{
		$backup_dir = get_config_val("backup");
		$days = get_config_p1("backup")*3600*24;
		if ($days<1) $days = 3600*24;
		$d = opendir($backup_dir);
		while ($f = readdir($d))
		{
			if (is_file($backup_dir."/".$f) && stristr($f,".sql.gz"))
			{
			 	if (filectime($backup_dir."/".$f)<time()-$days )
			 	{
			 		unlink($backup_dir."/".$f);
			 	}
			}
		}
		closedir($d);
	}

	function print_fs_error_msg($string,$title="Fehler!")
	{
		echo "<table style=\"width:80%;margin:10px auto;border:1px solid #fff;border-collapse:collapse\">";
		echo "<tr><th class=\"tbltitle\">$title</th></tr>";
		echo "<tr><td class=\"tbldata\">$string</td></tr>";
		echo "</table>";
		echo "</body></html>";
		exit;
	}

	function get_denied_ips()
	{
		global $db_table;
		$res = dbquery("SELECT * FROM ".$db_table['ip_deny'].";");
		if (mysql_num_rows($res)>0)
		{
			while ($arr=mysql_fetch_array($res))
			{
				$ips[$arr['deny_ip']]=$arr['deny_reason'];
			}
		}
		return $ips;
	}

	function get_gamerounds()
	{
		global $db_table;
		$res = dbquery("SELECT * FROM ".$db_table['rounds']." WHERE round_active=1 ORDER BY round_name;");
		if (mysql_num_rows($res)>0)
		{
			while ($arr=mysql_fetch_array($res))
			{
				$rounds[$arr['round_id']]['name']=$arr['round_name'];
				$rounds[$arr['round_id']]['url']=$arr['round_url'];
				$rounds[$arr['round_id']]['startdate']=$arr['round_startdate'];
				$rounds[$arr['round_id']]['active']=$arr['round_active'];
			}
		}
		return $rounds;
	}

	//
	// Serverstatus prüfen
	//
	function serverstatus($addr,$round_id=0)
	{
		global $db_table;
		$port=80;

		if (substr($addr,0,7)=="http://")
			$addr = substr($addr,7);
		if (stristr($addr,"/"))
			$addr = substr($addr,0,strpos($addr,"/"));


		$churl = @fsockopen($addr, $port, $errno, $errstr, 5);
	  if (!$churl)
	  {
			//echo "$addr ist offline!<br/>";
			$status=0;
			dbquery("INSERT INTO logs (log_keyword,log_timestamp,log_text) VALUES ('serverstatus','".time()."','Der Server $addr ist offline');");
		}
		else
		{
			//echo "$addr ist online!<br/>";
			$status=1;
		}
		if ($round_id>0)
			dbquery("UPDATE ".$db_table['rounds']." SET round_serverstatus=$status,round_serverstatus_time=".time()." WHERE round_id=".$round_id.";");
	}



	function generateCaptchaString()
  {
		$len = 4;
	  $text="";
	  for ($x=0;$x<$len;$x++)
	  {
	  	$text.=rand(0,9);
	  }
	  return $text;
	}

  function encryptCaptchaString($string, $key)
  {
	   $result = '';
	   for($i=0; $i<strlen($string); $i++)
	   {
	      $char = substr($string, $i, 1);
	      $keychar = substr($key, ($i % strlen($key))-1, 1);
	      $char = chr(ord($char)+ord($keychar));
	      $result.=$char;
	   }
   	$result = base64_encode($result);
   	$result  = str_replace("=", "", $result);
   	return $result;
  }

	// Tipmessage
	function tm($title,$text,$mouse=0)
	{
		$text = str_replace('"',"\'",$text);
		if($mouse==0)
		{
			return "onMouseOver=\"stm(['".$title."','".$text."'],stl)\" onMouseOut=\"htm()\"";
		}
		else
		{
			return "onclick=\"stm(['".$title."','".$text."'],stl)\" onMouseOut=\"htm()\"";
		}
	}


	function genfkey()
	{
		$_SESSION['encfkey'] = rand(1000,9999).rand(1000,9999).rand(1000,9999);
	}

	function encfname($name)
	{
		return md5($name.$_SESSION['encfkey']);
	}

	function forward($page,$debug=0)
	{
		if ($debug==0)
			header("Location: $page");
		echo "Falls die automatische Weiterleitung nicht klappt, <a href=\"".$page."\">hier</a> klicken." ;
		exit;
	}

	function forwardInternal($page,$debug=0)
	{
		forward("http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/".$page, $debug);
	}

	function pushText($text)
	{
		$_SESSION['textstore']=$text;
	}

	function popText()
	{
		$text = "";
		if (isset($_SESSION['textstore']))
		{
			$text = $_SESSION['textstore'];
			unset($_SESSION['textstore']);
		}
		return $text;
	}

	function message($type,$msg)
	{
		return "<div class=\"messagebox\"><div class=\"".$type."\">".$msg."</div></div>";
	}

  function prettyUrlString($str)
{
	$ut = $str;
	$ut = str_replace("Ä","ae",$ut);
	$ut = str_replace("Ö","oe",$ut);
	$ut = str_replace("Ü","ue",$ut);
	$ut = str_replace("ä","ae",$ut);
	$ut = str_replace("ö","oe",$ut);
	$ut = str_replace("ü","ue",$ut);
	$ut = str_replace("ß","ss",$ut);
	$ut = str_replace('/',"-",$ut);
	$ut = str_replace(" ","-",$ut);
	$ut = str_replace("_","-",$ut);
	$ut = str_replace("..","",$ut);
	$ut = str_replace(".","-",$ut);
	$ut = str_replace("'","",$ut);
	$ut = str_replace('"',"",$ut);
	$ut = preg_replace("/[^a-z0-9-]/i","",$ut);
	$ut = strtolower($ut);
	return $ut;
}

/**
* Fetches the contents of a JSON config file and returns it as an associative array
*/
function fetchJsonConfig($file)	{
  $path = APP_PATH."/config/".$file;
  if (!file_exists($path))	{
    throw new Exception("Config file $file not found!");
  }
  $data = json_decode(file_get_contents($path), true);
  if (json_last_error() != JSON_ERROR_NONE)	{
    throw new Exception("Failed to parse config file $file (JSON error ".json_last_error().")!");
  }
  return $data;
}

?>
