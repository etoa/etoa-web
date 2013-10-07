<h1>Servermeldung</h1>
<?PHP

	if (isset($_POST['submit']))
	{
		dbquery("UPDATE 
		config 
		SET 
		config_value='".addslashes($_POST['config_value'])."',
		config_param1='".time()."',
		config_param2='".addslashes($_POST['config_param2'])."'
		WHERE config_name='server_notice';");
		echo "Gespeichert!";
	}

	$res=dbquery("SELECT * FROM config WHERE config_name='server_notice';");
	echo "<form action=\"?page=$page\" method=\"post\">";
	if (mysql_num_rows($res)>0)
	{
		$arr=mysql_fetch_array($res);
		echo "<textarea name=\"config_value\" rows=\"10\" cols=\"120\">".stripslashes($arr['config_value'])."</textarea><br/>";
		echo "Farbe: <input size=\"12\" name=\"config_param2\" value=\"".stripslashes($arr['config_param2'])."\" /><br/>";
		echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";
	}
	else
		echo "<i>Keine Texte vorhanden!</i><br/><br/>";
	echo "</form>";
?>
