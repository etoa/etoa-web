<h1>Runden</h1>
<?PHP
	if (isset($_POST['submit']) || isset($_POST['submit_new']))
	{
		if (count	($_POST['round_name'])>0)
		{
			foreach ($_POST['round_name'] as $k=>$v)
			{
				if ($_POST['round_name'][$k]!="" && $_POST['round_url'][$k]!="")			
					dbquery("UPDATE rounds SET round_name='".$_POST['round_name'][$k]."', round_url='".$_POST['round_url'][$k]."', round_active='".$_POST['round_active'][$k]."' WHERE round_id=$k");
			}		
			echo "&Auml;nderungen gespeichert!<br/><br/>";	
		}
		if (count($_POST['round_del'])>0)
		{
			foreach ($_POST['round_del'] as $k=>$v)
			{
				if ($v==1)
					dbquery("DELETE FROM rounds WHERE round_id=$k");
			}
		}
	}
	if (isset($_POST['submit_new']))
	{
		dbquery("INSERT INTO rounds (round_active) VALUES(0);");
	}


	$res=dbquery("SELECT * FROM rounds ORDER BY round_active DESC, round_name ASC;");
	echo "<form action=\"?page=$page\" method=\"post\">";
	if (mysql_num_rows($res)>0)
	{
		echo "<table class=\"tbl\" style=\"width:750px;\">";
		echo "<tr><th>Name:</th><th>Url:</th><th>Anzeigen:</th><th>Löschen:</th></tr>";
		while ($arr=mysql_fetch_array($res))
		{
			echo "<tr><td><input type=\"text\" name=\"round_name[".$arr['round_id']."]\" value=\"".$arr['round_name']."\" size=\"20\" /></td>";
			echo "<td><input type=\"text\" name=\"round_url[".$arr['round_id']."]\" value=\"".$arr['round_url']."\" size=\"50\" /></td>";
			echo "<td><input type=\"radio\" name=\"round_active[".$arr['round_id']."]\" value=\"1\" checked=\"checked\" /> Ja <input type=\"radio\" name=\"round_active[".$arr['round_id']."]\" value=\"0\"";
			if ($arr['round_active']==0)
				echo "checked=\"checked\"";
			echo "/> Nein</td>";	
			echo "<td><input type=\"checkbox\" name=\"round_del[".$arr['round_id']."]\" value=\"1\"/></td></tr>";
		}		
		echo "</table><br/><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";
	}
	else
		echo "<i>Keine Runden vorhanden!</i><br/><br/>";
		
	echo "<input type=\"submit\" name=\"submit_new\" value=\"Neue Runde\" /></form>";

?>