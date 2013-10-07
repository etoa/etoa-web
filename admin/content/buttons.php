<h1>Buttons</h1>
<?PHP

	if (isset($_POST['submit']))
	{
		dbquery("UPDATE 
		config 
		SET 
		config_value='".addslashes($_POST['config_value'])."'
		WHERE config_name='buttons';");
		echo "Gespeichert!";
	}

	$res=dbquery("SELECT * FROM config WHERE config_name='buttons';");
	echo "<form action=\"?page=$page\" method=\"post\">";
	if (mysql_num_rows($res)>0)
	{
		$arr=mysql_fetch_array($res);
		echo "<textarea name=\"config_value\" rows=\"30\" cols=\"120\">".stripslashes($arr['config_value'])."</textarea>";
		echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";
	}
	else
		echo "<i>Keine Texte vorhanden!</i><br/><br/>";
	echo "</form>";
?>
