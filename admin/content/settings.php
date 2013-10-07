<h1>Einstellungen</h1>
<?PHP
	if (isset($_POST['submit']) || isset($_POST['submit_new']))
	{
		if (count	($_POST['config_name'])>0)
		{
			foreach ($_POST['config_name'] as $k=>$v)
			{
				dbquery("UPDATE config SET config_name='".$_POST['config_name'][$k]."',config_value='".$_POST['config_value'][$k]."',config_param1='".$_POST['config_param1'][$k]."',config_param2='".$_POST['config_param2'][$k]."' WHERE config_id=$k");
			}		
			echo "&Auml;nderungen gespeichert!<br/><br/>";	
		}
		if (count($_POST['config_del'])>0)
		{
			foreach ($_POST['config_del'] as $k=>$v)
			{
				if ($v==1)
					dbquery("DELETE FROM config WHERE config_id=$k");
			}
		}
	}
	if (isset($_POST['submit_new']))
	{
		dbquery("INSERT INTO config () VALUES ();");
	}


	$res=dbquery("SELECT * FROM config;");
	echo "<form action=\"?page=$page\" method=\"post\">";
	if (mysql_num_rows($res)>0)
	{
		echo "<table class=\"tbl\">";
		echo "<tr><th>Name:</th><th>Wert:</th><th>Parameter 1:</th><th>Parameter 2:</th><th>Löschen:</th></tr>";
		while ($arr=mysql_fetch_array($res))
		{
			echo "<tr><td><input type=\"text\" name=\"config_name[".$arr['config_id']."]\" value=\"".$arr['config_name']."\" size=\"20\" /></td>";
			echo "<td><textarea name=\"config_value[".$arr['config_id']."]\" rows=\"3\" cols=\"20\">".$arr['config_value']."</textarea></td>";
			echo "<td><textarea name=\"config_param1[".$arr['config_id']."]\" rows=\"3\" cols=\"20\">".$arr['config_param1']."</textarea></td>";
			echo "<td><textarea name=\"config_param2[".$arr['config_id']."]\" rows=\"3\" cols=\"20\">".$arr['config_param2']."</textarea></td>";
			echo "<td><input type=\"checkbox\" name=\"config_del[".$arr['config_id']."]\" value=\"1\"/></td></tr>";
		}		
		echo "</table><br/><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";
	}
	else
		echo "<i>Keine Runden vorhanden!</i><br/><br/>";
		
	echo "<input type=\"submit\" name=\"submit_new\" value=\"Neuer Eintrag\" /></form>";

?>