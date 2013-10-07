<br/>
<?PHP

	define('POSTS_TABLE',"wbb1_1_post");
	define('THREADS_TABLE',"wbb1_1_thread");
	define('RULES_THREAD_ID',$conf['rules_thread']['v']);

	$res=dbquery("SELECT * FROM ".POSTS_TABLE." WHERE threadid=".RULES_THREAD_ID." ORDER BY time ASC LIMIT 1;");
	echo "<div class=\"boxLine\"></div>";
	if (mysql_num_rows($res)>0)
	{
		$arr = mysql_fetch_array($res);
		echo "<div class=\"boxTitle\"><h2>".$arr['subject']."</h2>";
		//if ($arr['edittime']>0) echo "<br/><span class=\"subtitle\">Letzte &Auml;nderung: ".df($arr['edittime'])."</span>";
		echo "</div>";
		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxData\">";
		echo text2html($arr["message"]);
	}
	else
	{
		echo "<div class=\"boxTitle\">Es trat ein Fehler auf!</div>";
		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxData\">";
		echo "<i>Regeln nicht vorhanden!</i>";
	}

	echo "</div>";
	echo "<div class=\"boxLine\"></div>";
?>