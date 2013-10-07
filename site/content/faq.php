<?PHP
echo "Die FAQ befindet sich neu <a href=\"help\">hier</a>!";


/*
	echo "<div class=\"boxLine\"></div>";

	if (count($_POST)==0)
	{
		genfkey();
	}
	

	if ($_GET['faq']>0)
	{
		$res=dbquery("
		SELECT
			*
		FROM 
			faq
		WHERE 
			faq_id=".intval($_GET['faq']).";
		");
		if (mysql_num_rows($res)>0)
		{
			$arr=mysql_fetch_array($res);
			echo "<div class=\"boxTitle\">Frage: ".text2html($arr['faq_question'])."</div>";
			echo "<div class=\"boxLine\"></div>";
			echo "<div class=\"boxData\">";
			echo text2html($arr['faq_answer']);
			echo '</div>';			
			echo '<div class="boxLine"></div>';
			echo "<div class=\"boxData\">";
			if ($arr['faq_ratings']>0)
			{
				echo "Bewertung: ".round($arr['faq_rating']/$arr['faq_ratings'],1).", ";
			}
			echo "Aufrufe: ".$arr['faq_views']."";
			if ($arr['faq_user_time']>0)
			{
				echo ", Hinzugefügt am ".date("d.m.Y",$arr['faq_user_time']);
			}
			echo '</div>';			
			echo '<div class="boxLine"></div><br/>';


			if ($_POST['rating']>0 && !$_SESSION['faq_'.$_GET['faq']])
			{
				dbquery("UPDATE faq SET faq_rating=faq_rating+".$_POST['rating'].",faq_ratings=faq_ratings+1 WHERE faq_id=".intval($_GET['faq']).";");
				echo "Vielen Dank f&uuml;r deine Bewertung!<br/><br/>";
				$_SESSION['faq_'.$_GET['faq']]=true;
			}
			if (!$_SESSION['faq_'.$_GET['faq']])
			{
				echo '<form action="?page='.$page.'&amp;faq='.$arr['faq_id'].'" method="post">';
				echo '<span style="border:1px solid #777;background:#000;padding:5px;">War diese Antwort hilfreich (1=schlecht, 5=gut): &nbsp; ';
				echo '<input type="radio" name="rating" value="1" /> 1 &nbsp;';
				echo '<input type="radio" name="rating" value="2" /> 2 &nbsp;';
				echo '<input type="radio" name="rating" value="3" checked="checked" /> 3 &nbsp;';
				echo '<input type="radio" name="rating" value="4" /> 4 &nbsp;';
				echo '<input type="radio" name="rating" value="5" /> 5  &nbsp;</span> &nbsp; <input type="submit" value="Bewerten" name="submit_rating" /></form>';
			}
			dbquery("UPDATE faq SET faq_views=faq_views+1 WHERE faq_id=".$arr['faq_id'].";");
			
			echo "<br/><div style=\"border:1px solid #777;background:#000;padding:5px;\">
			<b>Ähnliche Fragen:</b> ";
			$kws = explode("\r\n",$arr['faq_keywords']);
			$cnt=0;
			echo "<span style=\"font-size:8pt;\">(";
			foreach ($kws as $ks)
			{
				echo "$ks";			
				$cnt++;
				if ($cnt<count($kws))
				{
					echo ", ";
				}
			}
			echo ")</span><br/><table class=\"faq\">";
			$tdclass = "d0";
			$ids = array();
			$cnt = 0;
			foreach ($kws as $ks)
			{
				$sres = dbquery("
				SELECT
					*
				FROM
					faq
				WHERE
					faq_keywords LIKE '%".$ks."%'
					AND faq_id!=".$arr['faq_id']."
				;");
				if (mysql_num_rows($sres)>0)
				{
					while ($sarr = mysql_fetch_array($sres))
					{
						if (!in_array($sarr['faq_id'],$ids))
						{
							array_push($ids,$sarr['faq_id']);
							if ($tdclass == "d0")
								$tdclass = "d1";
							else
								$tdclass = "d0";
							echo "<tr class=\"$tdclass\">
							<th>".text2html($sarr['faq_question'])."</th>
							<td><a href=\"?page=$page&amp;faq=".$sarr['faq_id']."\">Zeigen</a></td>
							</tr>";
							$cnt++;
						}
					}
				}
			}
			echo "</table>";			
			if ($cnt==0)
				echo	"<i>Keine ähnlichen Themen gefunden</i>";
			echo "</div>";
						
			echo "<br/><div class=\"boxData\" style=\"border:1px solid #aaa;\">";
			echo "<h2>Kommentare</h2><br/>";
			if (isset($_POST[encfname('comment_submit')]))
			{
				if ($_POST[encfname('comment_nick')]!="" && $_POST[encfname('comment_email')]!="" && $_POST[encfname('comment_text')]!="")
				{
					dbquery("
					INSERT INTO
						faq_comments
					(
						comment_time,
						comment_nick,
						comment_email,
						comment_text,
						comment_client,
						comment_host,
						comment_ip,
						comment_faq_id
					)
					VALUES
					(
						UNIX_TIMESTAMP(),
						'".addslashes($_POST[encfname('comment_nick')])."',
						'".addslashes($_POST[encfname('comment_email')])."',
						'".addslashes($_POST[encfname('comment_text')])."',
						'".$_SERVER['HTTP_USER_AGENT']."',
						'".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',						
						'".$_SERVER['REMOTE_ADDR']."',
						".$arr['faq_id']."
					);");				
					echo "<span style=\"color:#0f0\">Vielen Dank für deinen Kommentar!</span><br/><br/>";
					$text = $_POST[encfname('comment_nick')]." (".$_POST[encfname('comment_email')].") hat einen neuen Kommentar zur Frage\n\n".$arr['faq_question']."\n\ngeschrieben:\n\n".$_POST[encfname('comment_text')];
					$text .= "\n\nUser-Agent: ".$_SERVER['HTTP_USER_AGENT']."\nHost: ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."";
					$text .= "\n\nhttp://www.etoa.ch/?page=faq&faq=".$arr['faq_id']."";
					mail($conf['faq_admin']['v'],"EtoA-FAQ: Neuer Kommentar",$text);
				}
				else
				{
					echo "Fehler! Nicht alle Felder ausgefüllt!<br/><br/>";
				}
				genfkey();
			}
			else
			{
				genfkey();
			}
			
			
			echo "[<a href=\"javascript:;\" onclick=\"toggleBox('submitForm')\">Kommentar verfassen</a>]<br/><br/>
			<div id=\"submitForm\" style=\"display:none;\">
				<form action=\"?page=$page&amp;faq=".$arr['faq_id']."\" method=\"post\">
				<table>
					<tr>
						<th>Name:</th>
						<td><input type=\"text\" name=\"".encfname('comment_nick')."\" value=\"\" maxlength=\"25\" size=\"20\" />
						(Dein Foren- oder inGame-Nickname)</td>
					</tr>
					<tr>
						<th>E-Mail:</th>
						<td><input type=\"text\" name=\"".encfname('comment_email')."\" value=\"\" maxlength=\"50\" size=\"25\" />
						(Wird nicht veröffentlicht)</td>
					</tr>
					<tr>
						<th>Kommentar:</th>
						<td><textarea name=\"".encfname('comment_text')."\" rows=\"5\" cols=\"60\"></textarea></td>
					</tr>				
					<tr>
						<th></th>
						<td><input type=\"submit\" name=\"".encfname('comment_submit')."\" value=\"Einsenden\" /></td>
					</tr>				
				</table></form><span style=\"font-size:8pt;color:#f90;font-weight:bold;\">
				Achtung! Für Kommentare ebenfalls die Forenregeln. Wer spammt, beleidigt oder gegen sonstige Regeln
				verstösst kann im Spiel und im Forum gesperrt werden!<br/>
				IP, Hostname und Browserdaten werden aufgezeichnet!
				(".$_SERVER['HTTP_USER_AGENT'].", ".gethostbyaddr($_SERVER['REMOTE_ADDR']).")</span><br/><br/>
			</div>";
			$cres = dbquery("
			SELECT
				comment_text,
				comment_nick,
				comment_time
			FROM
				faq_comments
			WHERE
				comment_faq_id=".$arr['faq_id']."
			ORDER BY
				comment_time DESC;
			");
			$nr = mysql_num_rows($cres);
			if ($nr>0)
			{
				echo "Es sind $nr Kommentare vorhanden:<br/>";
				while ($carr=mysql_fetch_array($cres))
				{
					echo "<hr/>".text2html($carr['comment_text'])."<br/><span style=\"font-size:8pt;color:#ddd;font-weight:bold;\">
					".stripslashes($carr['comment_nick']).", ".df($carr['comment_time'])."
					</span><br/>";
				}
			}
			else
			{
				echo "<i>Keine Kommentare vorhanden!</i>";
			}			
			echo '</div>';							
			
		}	
		else
		{
			echo "Frage nicht vorhanden!";
		}		
		echo "<br/><input type=\"button\" onclick=\"document.location='?page=$page&amp;cat=".$arr['faq_cat_id']."'\" value=\"Zur&uuml;ck zu den Fragen\" />";
	}	
	

	elseif ($_GET['cat']>0)
	{
		$cres=dbquery("
		SELECT
			cat_name
		FROM 
			faq_cat
		WHERE 
			cat_id=".intval($_GET['cat']).";
		");
		if (mysql_num_rows($cres)>0)
		{
			$carr=mysql_fetch_array($cres);
			echo "<div class=\"boxTitle\">Kategorie: ".text2html($carr['cat_name'])."</div>";
			echo "<div class=\"boxLine\"></div>";
			echo "<div class=\"boxData\">";
			$res=dbquery("
			SELECT
				faq_question,
				faq_id
			FROM 
				faq
			WHERE
				faq_cat_id=".intval($_GET['cat'])."
			ORDER BY
				faq_question;
			");
			if (mysql_num_rows($res)>0)
			{
				echo '<table class="faq">';
				$tdclass = "d0";
				while ($arr=mysql_fetch_array($res))
				{
					if ($tdclass == "d0")
						$tdclass = "d1";
					else
						$tdclass = "d0";
					echo "<tr class=\"$tdclass\"><td>".text2html($arr['faq_question']);
					$cres = dbquery("
						SELECT
							COUNT(comment_id)
						FROM
							faq_comments
						WHERE
							comment_faq_id=".$arr['faq_id']."
						");
					$carr = mysql_fetch_row($cres);
					if ($carr[0]>0)
					{
						echo "&nbsp; <span style=\"font-size:8pt;\"> ".$carr[0]." Kommentare</span>";
					}					
					
					echo "</td>
					<td><a href=\"?page=$page&amp;faq=".$arr['faq_id']."\">Zeigen</a></td>";
					echo "</tr>";
				}		
				echo '</table>';
			}
			else
				echo "Keine Daten vorhanden!";
			echo "</div>";			
			echo "<div class=\"boxLine\"></div>";
		}	
		else
		{
			echo "Kategorie nicht vorhanden!";
		}
		echo "<br/><input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"Zur&uuml;ck zur &Uuml;bersicht\" />";	
	}
	

	elseif ($_GET['cat']==-1)
	{
			echo "<div class=\"boxTitle\">Popul&auml;re Fragen</div>";
			echo "<div class=\"boxLine\"></div>";
			echo "<div class=\"boxData\">";
			$res=dbquery("
			SELECT
				faq_question,
				faq_id,
				faq_views,
				cat_name
			FROM 
				faq
			INNER JOIN
				faq_cat
				ON faq_cat_id=cat_id
			ORDER BY
				faq_views DESC
			LIMIT 
				10;
			");
			if (mysql_num_rows($res)>0)
			{
				echo "<table class=\"faq\">";
				$tdclass = "d0";
				while ($arr=mysql_fetch_array($res))
				{
					if ($tdclass == "d0")
						$tdclass = "d1";
					else
						$tdclass = "d0";
					echo "<tr class=\"$tdclass\"><th>".text2html($arr['cat_name'])."</th>
					<td>".text2html($arr['faq_question']);
					$cres = dbquery("
						SELECT
							COUNT(comment_id)
						FROM
							faq_comments
						WHERE
							comment_faq_id=".$arr['faq_id']."
						");
					$carr = mysql_fetch_row($cres);
					if ($carr[0]>0)
					{
						echo "&nbsp; <span style=\"font-size:8pt;\"> ".$carr[0]." Kommentare</span>";
					}					
					echo "</td>
					<td>".$arr['faq_views']."</td>
					<td><a href=\"?page=$page&amp;faq=".$arr['faq_id']."\">Zeigen</a></td>";
					echo "</tr>";
				}		
				echo '</table>';
			}
			else
			{
				echo "Keine Daten vorhanden!";
			}
			echo "</div>";			
			echo "<div class=\"boxLine\"></div>";
		echo "<br/><input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"Zur&uuml;ck zur &Uuml;bersicht\" />";	
	}
	

	elseif ($_GET['cat']==-2)
	{
			echo "<div class=\"boxTitle\">Bestbewertete Fragen</div>";
			echo "<div class=\"boxLine\"></div>";
			echo "<div class=\"boxData\">";
			$res=dbquery("
			SELECT
				faq_question,
				faq_id,
				faq_rating/faq_ratings AS rating,
				cat_name
			FROM 
				faq
			INNER JOIN
				faq_cat
				ON faq_cat_id=cat_id
			ORDER BY
				rating DESC
			LIMIT 
				10;
			");
			if (mysql_num_rows($res)>0)
			{
				echo "<table class=\"faq\">";
				$tdclass = "d0";
				while ($arr=mysql_fetch_array($res))
				{
					if ($tdclass == "d0")
						$tdclass = "d1";
					else
						$tdclass = "d0";
					echo "<tr class=\"$tdclass\"><th>".text2html($arr['cat_name'])."</th>
					<td>".text2html($arr['faq_question']);
					$cres = dbquery("
						SELECT
							COUNT(comment_id)
						FROM
							faq_comments
						WHERE
							comment_faq_id=".$arr['faq_id']."
						");
					$carr = mysql_fetch_row($cres);
					if ($carr[0]>0)
					{
						echo "&nbsp; <span style=\"font-size:8pt;\"> ".$carr[0]." Kommentare</span>";
					}
					echo "</td>
					<td>".round($arr['rating'],1)."</td>
					<td><a href=\"?page=$page&amp;faq=".$arr['faq_id']."\">Zeigen</a></td>
					";					
					echo "</tr>";
				}		
				echo '</table>';
			}
			else
			{
				echo "Keine Daten vorhanden!";
			}
			echo "</div>";			
			echo "<div class=\"boxLine\"></div>";
		echo "<br/><input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"Zur&uuml;ck zur &Uuml;bersicht\" />";	
	}
	

	elseif ($_GET['cat']==-3)
	{
			echo "<div class=\"boxTitle\">Neuste Fragen</div>";
			echo "<div class=\"boxLine\"></div>";
			echo "<div class=\"boxData\">";
			$res=dbquery("
			SELECT
				faq_question,
				faq_id,
				faq_user_time,
				cat_name
			FROM 
				faq
			INNER JOIN
				faq_cat
				ON faq_cat_id=cat_id
			ORDER BY
				faq_user_time DESC
			LIMIT 
				10;
			");
			if (mysql_num_rows($res)>0)
			{
				echo '<table class="faq">';
				$tdclass = "d0";
				while ($arr=mysql_fetch_array($res))
				{
					if ($tdclass == "d0")
						$tdclass = "d1";
					else
						$tdclass = "d0";
					echo "<tr class=\"$tdclass\"><th>".text2html($arr['cat_name'])."</th>
					<td>".text2html($arr['faq_question']);
					$cres = dbquery("
						SELECT
							COUNT(comment_id)
						FROM
							faq_comments
						WHERE
							comment_faq_id=".$arr['faq_id']."
						");
					$carr = mysql_fetch_row($cres);
					if ($carr[0]>0)
					{
						echo "&nbsp; <span style=\"font-size:8pt;\"> ".$carr[0]." Kommentare</span>";
					}
					echo "</td>
					<td>".date("d.m.Y",$arr['faq_user_time'])."</td>
					<td><a href=\"?page=$page&amp;faq=".$arr['faq_id']."\">Zeigen</a></td>";
					echo "</tr>";
				}		
				echo '</table>';
			}
			else
			{
				echo "Keine Daten vorhanden!";
			}
			echo "</div>";			
			echo "<div class=\"boxLine\"></div>";
		echo "<br/><input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"Zur&uuml;ck zur &Uuml;bersicht\" />";	
	}
	

	elseif (($_POST['faq_search']!="" && ($_POST['faq_search_keyword']!="" || $_POST['faq_search_fulltext']!="")) || $_GET['s']!="")
	{
		echo "<div class=\"boxTitle\">Frequently asked questions - Suchergebnisse</div>";
		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxData\">";	
		if ($_GET['s']!="")
		{
			$s = $_GET['s'];		
			$_POST['faq_search'] = $s;	
			$sql="SELECT
				faq_question,
				faq_id
			FROM 
				faq
			WHERE
				faq_answer LIKE '%".$s."%'
				OR faq_question LIKE '%".$s."%'
				OR faq_keywords LIKE '%".$s."%'
			ORDER BY
				faq_question;";		
			
		}	
		elseif ($_POST['faq_search_keyword']!="")
		{
			$sql="SELECT
				faq_question,
				faq_id
			FROM 
				faq
			WHERE
				faq_keywords LIKE '%".$_POST['faq_search']."%'
			ORDER BY
				faq_question;";
		}
		else
		{		
			$sql="SELECT
				faq_question,
				faq_id
			FROM 
				faq
			WHERE
				faq_answer LIKE '%".$_POST['faq_search']."%'
				OR faq_question LIKE '%".$_POST['faq_search']."%'
			ORDER BY
				faq_question;";
		}
		$res=dbquery($sql);
		if (mysql_num_rows($res)>0)
		{
			echo 'Suchergebnisse f&uuml;r <b>'.$_POST['faq_search'].'</b>:<br/><br/>';
				echo "<table class=\"faq\">";
				$tdclass = "d0";
				while ($arr=mysql_fetch_array($res))
				{
					if ($tdclass == "d0")
						$tdclass = "d1";
					else
						$tdclass = "d0";
					echo "<tr class=\"$tdclass\"><th>".$arr['faq_question']."</th>
					<td><a href=\"?page=$page&amp;faq=".$arr['faq_id']."\">Zeigen</a></td>
				</tr>";
			}
			echo '</table><br/>';
		}
		else
		{
			echo "<i>Deine Suche nach <b>".$_POST['faq_search']."</b> ergab keine Treffer!</i><br/><br/>";
		}
		echo '<input type="button" value="Neue Suche" onclick="document.location=\'?page='.$page.'\'" />';
		echo "</div>";
		echo "<div class=\"boxLine\"></div><br/>";		
	}	


	elseif ($_POST['faq_user_question_submit']!="" && $_POST['faq_user_question']!="")
	{
		echo "<div class=\"boxTitle\">Frequently asked questions - Frage einsenden</div>";
		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxData\">";		
		$res=dbquery("SELECT
			faq_id
		FROM
			faq
		WHERE 
			faq_question='".stripslashes($_POST['faq_user_question'])."'
		;");
		if (mysql_num_rows($res)==0)
		{
			dbquery("INSERT INTO
				faq
			(
				faq_question,
				faq_user_nick,
				faq_user_email,
				faq_user_time,
				faq_user_ip,
				faq_user_host,
				faq_user_client,
				faq_cat_id
			) VALUES (
				'".addslashes($_POST['faq_user_question'])."',
				'".addslashes($_POST['faq_user_nick'])."',
				'".addslashes($_POST['faq_user_email'])."',
				".time().",
				'".$_SERVER['REMOTE_ADDR']."',
				'".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',
				'".$_SERVER['HTTP_USER_AGENT']."',
				0
			);");
			echo '<span style=\"color:#0f0\">Vielen Dank, deine Frage wurde gespeichert!</span><br/><br/>';
			$text = $_POST['faq_user_nick']." (".$_POST['faq_user_email'].") hat eine neue Frage geschrieben:\n\n".$_POST['faq_user_question'];
			mail($conf['faq_admin']['v'],"EtoA-FAQ: Neue Frage",$text);
			
		}
		else
		{
			echo 'Tut uns leid, diese Frage ist bereits vorhanden!<br/><br/>';
		}
		echo '<input type="button" value="Zur &Uuml;bersicht" onclick="document.location=\'?page='.$page.'\'" />';
		echo "</div>";
		echo "<div class=\"boxLine\"></div><br/>";		
	}	

	else
	{
		echo "<div class=\"boxTitle\">Frequently asked questions - &Uuml;bersicht</div>";
		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxData\">";
		echo "Willkommen im EtoA-Hilfecenter. Auf dieser Seite findest du häufig gestellte Fragen und die Antworten dazu. Sollte deine Frage nicht dabei sein, dann 
		stelle eine neue Frage mit dem Formular weiter unten auf dieser Seite!<br/>";
		$res=dbquery("
		SELECT
			cat_name,
			cat_id,
			COUNT(*) as cnt
		FROM 
			faq_cat
		INNER JOIN
			faq
		ON
			faq_cat_id=cat_id
		GROUP BY
			cat_id
		ORDER BY
			cat_order,
			cat_name;
		");
		if (mysql_num_rows($res)>0)
		{
			echo '<ul>';
			while ($arr=mysql_fetch_array($res))
			{
				echo "<li><a href=\"?page=$page&amp;cat=".$arr['cat_id']."\">".text2html($arr['cat_name'])."</a> (".$arr['cnt']." Eintr&auml;ge)</li>";
			}		
			echo "<br/><li><a href=\"?page=$page&amp;cat=-1\">Popul&auml;re Fragen (Top 10)</a></li>";
			echo "<li><a href=\"?page=$page&amp;cat=-2\">Bestbewertete Fragen (Top 10)</a></li>";
			echo "<li><a href=\"?page=$page&amp;cat=-3\">Neuste Fragen</a></li>";
			echo '</ul>';
			$res=dbquery("
			SELECT
				COUNT(faq_id)
			FROM
				faq
			WHERE
				faq_cat_id>0;
			");
			$arr=mysql_fetch_row($res);
			echo "&nbsp;&nbsp; Momentan befinden sich ".$arr[0]." Fragen und Antworten in der Datenbank!";
		}
		else
			echo "Keine Daten vorhanden!";
			
		echo '<br/><br/><form action="?page='.$page.'" method="post"><b>Suche:</b> 
			&nbsp; <input type="text" name="faq_search" size="30" maxlength="30" /> &nbsp; 
			<input type="submit" name="faq_search_keyword" value="Stichwort-Suche" /> &nbsp; 
			<input type="submit" name="faq_search_fulltext" value="Volltext-Suche" /></form></div>';
		echo "<div class=\"boxLine\"></div><br/>";

		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxTitle\">Frage stellen</div>";
		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxData\">";		
		echo '<form action="?page='.$page.'" method="post">
			&nbsp; Stelle hier deine Frage, von der du denkst dass sie f&uuml;r die anderen Spieler auch<br/>
			&nbsp; interessant sein k&ouml;nnte.<br/>
			&nbsp; Die Frage wird anschliessend durch einen Admin beantwortet und freigeschaltet:<br/><br/>
			&nbsp; Name: <input type="text" name="faq_user_nick" size="30" maxlength="50" /><br/> &nbsp; 
			E-Mail (bei Unklarheiten): <input type="text" name="faq_user_email" size="30" maxlength="50" /><br/>
			&nbsp; Frage (max. 200 Zeichen): <input type="text" name="faq_user_question" size="50" maxlength="200" /> &nbsp; 
			<input type="submit" name="faq_user_question_submit" value="Einsenden" /></form></div>';
		echo "<div class=\"boxLine\"></div><br/>";		

		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxTitle\">Andere Hilferessourcen</div>";
		echo "<div class=\"boxLine\"></div>";
		echo "<div class=\"boxData\">";	
		echo '<b>InGame Hilfe</b> <select onchange="if (this.options[this.selectedIndex].value!=\'\'){window.open(this.options[this.selectedIndex].value+\'/show.php?page=help\')};">';
		echo '<option value="">Runde w&auml;hlen...</option>';
		foreach ($rounds as $k=>$v)
		{
			echo "<option value=\"".$v['url']."\">".$v['name']."</option>";
		}	
		echo '</select> <br/>
		<b>Online-Forum:</b> <a href="http://forum.etoa.ch">http://forum.etoa.ch</a><br/>
		<b>Hilfe per E-Mail:</b> <a href="mailto:help@etoa.ch">help [at] etoa.ch</a><br/>
		<b>Hilfebereich des Forums</b> <a href=http://etoa.dysign.ch/forum/board.php?boardid=15">FAQ, Help und Infos</a>';		
		echo '</div>';
		echo "<div class=\"boxLine\"></div><br/>";

		
	}*/
?>