<h1>Java-Script Code</h1>
<?PHP

	if (isset($_POST['submit']))
	{
		dbquery("UPDATE 
		config 
		SET 
		config_value='".addslashes($_POST['indexjscript'])."'
		WHERE config_name='indexjscript';");
		dbquery("UPDATE 
		config 
		SET 
		config_value='".addslashes($_POST['footer_js'])."'
		WHERE config_name='footer_js';");		
		echo "Gespeichert!";
	}

	echo "<form action=\"?page=$page\" method=\"post\">";

	$res=dbquery("SELECT * FROM config WHERE config_name='indexjscript';");
	if (mysql_num_rows($res)>0)
	{
		$arr=mysql_fetch_array($res);
		echo "Header<br/>";
		echo "<textarea name=\"indexjscript\" rows=\"30\" cols=\"120\">".stripslashes($arr['config_value'])."</textarea><br/>";
	}

	$res=dbquery("SELECT * FROM config WHERE config_name='footer_js';");
	if (mysql_num_rows($res)>0)
	{
		$arr=mysql_fetch_array($res);
		echo "Footer<br/>";
		echo "<textarea name=\"footer_js\" rows=\"30\" cols=\"120\">".stripslashes($arr['config_value'])."</textarea><br/>";
	}
	echo "<p><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /></p>";
	
	echo "</form>";
?>
