<?PHP
	echo "<br/><div class=\"boxLine\"></div>";
	echo "<div class=\"boxTitle\">Neues Passwort anfordern</div>";
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxData\">";
	echo "Bitte w&auml;hle die Runde aus, in der sich dein Account befindet:<br/><br/>";
	foreach ($rounds as $k=>$v)
	{
		echo "<a href=\"".$v['url']."/show.php?index=pwforgot\">".$v['name']."</a>";
		if ($v['startdate']>0) 
			echo " (online seit ".date("d.m.Y",$v['startdate']).")";
		echo "<br/>";
	}
	echo "</div>";
	echo "<div class=\"boxLine\"></div>";
?>