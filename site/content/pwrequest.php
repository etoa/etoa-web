<?PHP
	echo "<br/><div class=\"boxLine\"></div>";
	echo "<div class=\"boxTitle\"><h2>Neues Passwort anfordern</h2></div>";
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxData\">";
	echo "Bitte w&auml;hle die Runde aus, in der sich dein Account befindet:<ul>";
	foreach ($rounds as $k=>$v)
	{
		echo "<li><a href=\"".$v['url']."/show.php?index=pwforgot\">".$v['name']."</a>";
		if ($v['startdate']>0) 
			echo " (online seit ".date("d.m.Y",$v['startdate']).")";
		echo "</li>";
	}
	echo "</ul>";
	echo "</div>";
	echo "<div class=\"boxLine\"></div>";
?>