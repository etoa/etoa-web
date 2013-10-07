<?PHP
	echo "<br/><div class=\"boxLine\"></div>";
	echo "<div class=\"boxTitle\">Ranglisten</div>";
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxData\">";
	echo "Bitte w&auml;hle die Runde aus, in der sich dein Account befindet:<br/><br/>";
	foreach ($rounds as $k=>$v)
	{
		echo "<a href=\"".$v['url']."/show.php?index=stats\">".$v['name']."</a>";
		if ($v['startdate']>0) 
			echo " (online seit ".date("d.m.Y",$v['startdate']).")";
		echo "<br/>";
	}
	echo "</div>";
	echo "<div class=\"boxLine\"></div>";
	
	echo "<br/><div class=\"boxLine\"></div>";
	echo "<div class=\"boxTitle\">Spielstatistiken</div>";
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxData\">";
	echo "Bitte w&auml;hle eine Runde:<br/><br/>";
	foreach ($rounds as $k=>$v)
	{
		echo "<a href=\"".$v['url']."/show.php?index=gamestats\">".$v['name']."</a>";
		if ($v['startdate']>0) 
			echo " (online seit ".date("d.m.Y",$v['startdate']).")";
		echo "<br/>";
	}
	echo "</div>";
	echo "<div class=\"boxLine\"></div>";
	
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
