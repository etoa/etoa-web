<div id="innercontent">
	
<h1>Willkommen im EtoA-Hilfe-Center</h1>

<table class="indextable">
<tr><td>
	<h3>FAQ</h3>
	<a href="?page=faq"><img src="web/images/icons/help.png" /></a><br/><br/>
	<?PHP
	$res=dbquery("
	SELECT
		COUNT(faq_id)
	FROM
		faq
	WHERE
		faq_deleted=0
	;");
	$arr = mysql_fetch_row($res);
	echo "<b>".$arr[0]."</b> Fragen in der <a href=\"?page=faq\">FAQ</a><br/>zuletzt aktualisiert ";
	
	$res=dbquery("
	SELECT
		faq_id,
		faq_updated
	FROM 
		faq
	WHERE
		faq_deleted=0
	ORDER BY
		faq_updated DESC
	LIMIT 
		1;");
	$arr = mysql_fetch_array($res);
	echo "<a href=\"?page=faq&amp;faq=".$arr['faq_id']."\">".tfs(time() - $arr['faq_updated'])."</a>";
	?>		
</td>
<td>
	<h3>Wiki</h3>
	<a href="?page=article"><img src="web/images/icons/Documents.png" /></a><br/><br/>
	<?PHP
		$res=dbquery("
		SELECT count(*) FROM (SELECT hash FROM (SELECT
			title,
			changed,
			id,
			hash,
			rev
		FROM
			articles a
		ORDER BY
			title ASC,rev DESC) AS a
		GROUP BY hash) as b
		;");
		$arr = mysql_fetch_row($res);
		echo "<b>".$arr[0]."</b> Artikel im <a href=\"?page=article\">Wiki</a><br/>zuletzt aktualisiert ";	
		$res=dbquery("
		SELECT
			hash,
			changed
		FROM 
			articles
		ORDER BY
			changed DESC
		LIMIT 
			1;");
		$arr = mysql_fetch_array($res);
		echo " <a href=\"?page=article&amp;article=".$arr['hash']."\">".tfs(time() - $arr['changed'])."</a>";	
		?>	
</td>
<td>
	<h3>Runden</h3>
	<a href="?page=rounds"><img src="web/images/icons/earth.png" /></a><br/><br/>
	<?PHP
		$res = dbquery("SELECT * FROM ".$db_table['rounds']." WHERE round_active=1 ORDER BY round_name;");
		if (mysql_num_rows($res)>0)
		{
			while ($arr=mysql_fetch_array($res))
			{
				echo '<a target="_blank" href="'.$arr['round_url'].'/show.php?index=help">Game-Hilfe '.$arr['round_name'].'</a><br/>';
			}
		}		
	?>
</td>
</tr>
<tr>
<td>

	<h3>Forum</h3>
	<a href="http://www.etoa.ch/forum"><img src="web/images/icons/chat.png" /></a><br/><br/>
	<a href="http://www.etoa.ch/forum/index.php?page=Board&boardID=13">Alles zum Forum</a><br/>
	<a href="http://www.etoa.ch/forum/index.php?page=Board&boardID=21">Technischer Support</a><br/>
	<a href="http://www.etoa.ch/forum/index.php?page=Board&boardID=15">Fragen und Antworten</a><br/>
	<a href="http://www.etoa.ch/forum/index.php?page=Team">Jemanden vom Team kontaktieren</a><br/>
	<a href="mailto:forum [ at ] etoa.ch">Mail an die Forenleitung</a>

</td>
<td>

	<h3>Entwicklung</h3>
	<a href="https://github.com/etoa"><img src="web/images/icons/Tools.png" /></a><br/><br/>
	<a href="https://github.com/etoa">Github</a><br/>
	<a href="http://etoa.ch/forum/index.php?page=Thread&threadID=8384">Fehler melden</a><br/>
	<a href="http://etoa.ch/forum/index.php?page=Board&boardID=44">Ideen und Vorschläge</a><br/>
	<a href="http://etoa.ch/forum/index.php?page=Board&boardID=76">Entwickler-Forum</a>
	
</td>
<td>

	<h3>Weitere Links</h3>
	<a href="http://downloads.etoa.ch"><img src="web/images/icons/star.png" /></a><br/><br/>
	<a href="http://downloads.etoa.ch">Downloads</a>	
	
</td>
</tr>
</table>

		
			
</div>
