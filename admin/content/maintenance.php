<h1>Wartungsmodus Login-Seite</h1>
<?PHP

	if (isset($_POST['submit']))
	{
		dbquery("UPDATE 
		config 
		SET 
		config_value='".addslashes($_POST['config_value'])."'
		WHERE config_name='maintenance_mode';");
		echo "Gespeichert!";
		$conf['maintenance_mode']['v'] = $_POST['config_value'];
	}

	$res=dbquery("SELECT * FROM config WHERE config_name='maintenance_mode';");
	echo "<form action=\"?page=$page\" method=\"post\">";
	if (mysql_num_rows($res)>0)
	{
		$arr=mysql_fetch_array($res);
		echo "<input type=\"radio\" value=\"1\" name=\"config_value\" ".($conf['maintenance_mode']['v']==1 ? ' checked="checked"' : '')." /> <span style=\"color:#f00\">Aktiv</span><br/>
					<input type=\"radio\" value=\"0\" name=\"config_value\" ".($conf['maintenance_mode']['v']==0 ? ' checked="checked"' : '')." /> <span style=\"color:#0f0\">Inaktiv</span>";
		echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";
	}

	echo "</form>";
?>
