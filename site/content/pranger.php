<?PHP
	echo "<br/><div class=\"boxLine\"></div>";
	echo "<div class=\"boxTitle\">Liste der gesperrten Spieler</div>";
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxData\">";
	echo "Bitte w&auml;hle eine Runde aus deren Pranger du anzeigen m&ouml;chtest:<br/><br/>";
	foreach ($rounds as $k=>$v)
	{
		echo "<a href=\"".$v['url']."/show.php?index=pillory\">".$v['name']."</a>";
		if ($v['startdate']>0) 
			echo " (online seit ".date("d.m.Y",$v['startdate']).")";
		echo "<br/>";
	}
	echo "</div>";
	echo "<div class=\"boxLine\"></div>";
?>