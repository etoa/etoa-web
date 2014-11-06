<?PHP
	echo "<br/><div class=\"boxLine\"></div>";
	echo "<div class=\"boxTitle\"><h2>Melde dich f&uuml;r eine Runde an</h2></div>";
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxData\">";
	echo "<h3>Rundenwahl</h3>
	Bitte w&auml;hle eine Runde aus:<ul>";
	foreach ($rounds as $k=>$v)
	{
		echo "<li><a href=\"".$v['url']."/show.php?index=register\">".$v['name']."</a>";
		if ($v['startdate']>0) 
			echo " (online seit ".date("d.m.Y",$v['startdate']).")";
		echo "</li>";
	}
	echo "</ul><h3>Voraussetzungen</h3>
	<ul>
	<li>Einen der unten aufgelisteten <b>Browser</b>, für andere Browser können wir keinen Support garantieren.</li>
	<li>Mindestauflösung 1024*768. Das Spiel ist optimiert für eine <b>Bildschirmauflösung von 1280*1024 Pixeln</b> (oder grösser).</li>
	<li>Dein Browser muss <b>JavaScript</b> aktiviert haben, damit das Spiel korrekt läuft!</li>
	<li>Java oder Flash wird <b>nicht</b> benötigt!</li>
	</ul>";

	echo '<h3>Testserver</h3>Um Zugang zum Testserver zu erhalten, schaue bitte den <a href="forum/thread.php?threadid=4226">Foren-Thread mit Infos
	zur Bewerbung als Beta-Tester</a> an. Falls du Beta-Tester bist, hast du Zugang zu einem speziellen Forenbereich wo auch 
	alle Daten zum Testserver-Login enthalten sind.';


	echo "<h3>Empfohlener Browser</h3>
	<table style=\"width:600px;\">
	<tr>
		<th style=\"border:none;width:130px;\">
			<a target=\"_blank\" href=\"http://www.getfirefox.com\">
				<img src=\"site/images/firefoxh.png\" alt=\"Mozilla Firefox\" style=\"width:100px;border:none;\" 
				onmouseover=\"this.src='site/images/firefox.png'\"  onmouseout=\"this.src='site/images/firefoxh.png'\" />
			</a>
		</th>
		<td style=\"vertical-align:top;text-align:left;\">
			<b>
				Mozilla Firefox
			</b><br/><br/>
			Sicher, schnell und beliebig erweiterbar. Der Firefox ist der empfohlene Browser für EtoA da wir auch mit diesem Entwickeln und die
			Seiten darauf optimieren. Da er Open-Source ist, läuft er auf allen gebräuchlichen Desktop-Betriebssystemen (Windows, Apple, GNU/Linux, BSD, Solaris, etc).
		</td>
	</tr>
	</table>
	<h3>Geeignete Browser</h3>
	<table style=\"width:600px;\">
	<tr>
		<th style=\"border:none;width:130px;\">
			<a target=\"_blank\" href=\"http://www.google.com/chrome\">
				<img src=\"site/images/chromeh.png\" alt=\"Chrome\" style=\"width:100px;border:none;\" 
				onmouseover=\"this.src='site/images/chrome.png'\"  onmouseout=\"this.src='site/images/chromeh.png'\"/>
			</a>
		</th>
		<td style=\"vertical-align:top;text-align:left;\">
			<b>
				Google Chrome
			</b>
			<br/><br/>
				Google Chrome ist ein Browser, der einfache Gestaltung mit fortschrittlicher Technologie kombiniert, um die Nutzung des Internets zu beschleunigen, zu vereinfachen und sicherer zu gestalten.
		</td>
	</tr>
	<tr>
		<th style=\"border:none;width:130px;\">
			<a target=\"_blank\" href=\"http://www.opera.com/download/\">
				<img src=\"site/images/operah.png\" alt=\"Opera\" style=\"width:100px;border:none;\" 
				onmouseover=\"this.src='site/images/opera.png'\"  onmouseout=\"this.src='site/images/operah.png'\"/>
			</a>
		</th>
		<td style=\"vertical-align:top;text-align:left;\">
			<b>
				Opera
			</b>
			<br/><br/>
			Auch Opera ist ein schneller und sicherer Browser mit vielen eingebauten Features.
		</td>
	</tr>
	<tr>
		<th style=\"border:none;\">
			<a target=\"_blank\" href=\"http://www.apple.com/de/safari/download\">
				<img src=\"site/images/safarih.png\" alt=\"Apple Safari\"  style=\"width:100px;border:none;\" 
				onmouseover=\"this.src='site/images/safari.png'\"  onmouseout=\"this.src='site/images/safarih.png'\"/>	
			</a>
		</th>
		<td style=\"vertical-align:top;text-align:left;\">
		<b>
			Apple Safari
		</b>
			<br/><br/>
			Apples Standardbrowser. Läuft nun auch nativ unter Windows.
		</td>
	</tr>	
	<tr>
		<th style=\"border:none;\">
			<a target=\"_blank\" href=\"http://www.microsoft.com/ie\">
				<img src=\"site/images/ie7h.png\" alt=\"Internet Explorer 7\"  style=\"width:100px;border:none;\" 
				onmouseover=\"this.src='site/images/ie7.png'\"  onmouseout=\"this.src='site/images/ie7h.png'\"/>	
			</a>
		</th>
		<td style=\"vertical-align:top;text-align:left;\">
		<b>
			Internet Explorer
		</b>
			<br/><br/>
			Der Standardbrowser von Microsoft. Wir empfehlen die Verwendung der neusten Version 8, die bei Windows 7 dabei ist
			oder sich nachträglich installieren lässt.
		</td>
	</tr>
	";
	echo "</table>";
	echo "</div>";
	echo "<div class=\"boxLine\"></div>";
?>
