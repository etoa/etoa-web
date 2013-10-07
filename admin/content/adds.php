<h1>Werbung</h1>
<?PHP

	if (isset($_POST['submit']))
	{
		dbquery("UPDATE 
		config 
		SET 
		config_value='".addslashes($_POST['adds'])."'
		WHERE config_name='adds';");
		dbquery("UPDATE 
		config 
		SET 
		config_value='".addslashes($_POST['adds_news'])."'
		WHERE config_name='adds_news';");

		echo "Gespeichert!";
	}

	echo "<form action=\"?page=$page\" method=\"post\">";

	$res=dbquery("SELECT * FROM config WHERE config_name='adds';");
	if (mysql_num_rows($res)>0)
	{
		$arr=mysql_fetch_array($res);
		echo "Reches Vertikalbanner:<br/><textarea name=\"adds\" rows=\"30\" cols=\"120\">".stripslashes($arr['config_value'])."</textarea><br/><br/>";
	}
	else
		echo "<i>Keine Texte vorhanden!</i><br/><br/>";

	$res=dbquery("SELECT * FROM config WHERE config_name='adds_news';");
	if (mysql_num_rows($res)>0)
	{
		$arr=mysql_fetch_array($res);
		echo "News Horizontalbanner:<br/><textarea name=\"adds_news\" rows=\"30\" cols=\"120\">".stripslashes($arr['config_value'])."</textarea>";
	}
	else
		echo "<i>Keine Texte vorhanden!</i><br/><br/>";
	echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";


	echo "</form>";
?>
