<?PHP
	if ($_GET['r']!="")
	{
		$url = base64_decode($_GET['r']);
		echo '<iframe style="border:none;" src="'.$url.'/show.php?page=help"  width="100%" height="100%" ></iframe>';
	}
	else
	{
		echo '
		<h1>Runden</h1>
			Bitte w&auml;hle eine Runde aus der Navigation, um deren Hilfe anzuzeigen!<br/>
			<h2>Status und Links</h2>';
			
			
			$res = dbquery("SELECT * FROM ".$db_table['rounds']." WHERE round_active=1 ORDER BY round_name;");
			if (mysql_num_rows($res)>0)
			{
				while ($arr=mysql_fetch_array($res))
				{
					echo "<fieldset style=\"float:left;width:250px;margin-right:20px;\">
					<legend>".$arr['round_name']."</legend>";
					echo '<ul>
						<li><a target="_blank" href="'.$arr['round_url'].'/show.php?index=register">Anmelden</a></li> 
						<li><a target="_blank" href="'.$arr['round_url'].'/show.php?index=pwforgot">Passwort vergessen</a></li> 
						<li><a target="_blank" href="'.$arr['round_url'].'/show.php?index=stats">Rangliste</a></li> 
						<li><a target="_blank" href="'.$arr['round_url'].'/show.php?index=gamestats">Rundenstatistiken</a></li> 
						<li><a target="_blank" href="'.$arr['round_url'].'/show.php?index=pillory">Pranger</a></li> 
						<li><a target="_blank" href="'.$arr['round_url'].'/show.php?index=feeds">RSS-Feeds</a></li> 
						<li><a target="_blank" href="'.$arr['round_url'].'/show.php?index=contact">Admin kontaktieren</a></li> 
						<li><a target="_blank" href="'.$arr['round_url'].'/show.php?index=help">Hilfe</a></li> 
					</ul>';
					echo "</fieldset>";	      							
				}
			}	
	}
	

?>
	
