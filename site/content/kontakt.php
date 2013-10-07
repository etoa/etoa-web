<?PHP
	echo "<br/><div class=\"boxLine\"></div>";
	echo "<div class=\"boxTitle\">Erhalte Hilfe bei Problemen</div>";
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxData\">";
	echo "Jede Runde wird von verschiedenen Game-Admins betreut. Diese sind InGame, im Forum und per E-Mail erreichbar.<br/><br/>
	Bitte w&auml;hle eine Runde aus, deren Kontakte du anzeigen m&ouml;chtest:<br/><br/>";
	foreach ($rounds as $k=>$v)
	{
		echo "<a href=\"".$v['url']."/show.php?index=contact\">".$v['name']."</a>";
		if ($v['startdate']>0) 
			echo " (online seit ".date("d.m.Y",$v['startdate']).")";
		echo "<br/>";
	}
	echo "</div>";
	echo "<div class=\"boxLine\"></div>";	
?>
