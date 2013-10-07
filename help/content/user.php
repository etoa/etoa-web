<?PHP
	define('AVATAR_DIR','/forum/wcf/images/avatars/');
	define('DEFAULT_AVATAR','avatar-default.png');

	if ($_GET['id'] > 0)
	{
		echo "<h1>Benutzerprofil</h1>";
		$res = dbquery("
		SELECT
			u.*,
			a.avatarExtension,
			a.avatarID
		FROM
			wcf1_user u
		LEFT JOIN
			wcf1_avatar a
			ON u.avatarID=a.avatarID
		WHERE
			u.userID='".$_GET['id']."'
		;");
		if (mysql_num_rows($res)>0)
		{
			$arr = mysql_Fetch_assoc($res);	
			echo "<h2>Profil von ".$arr['username']."</h2>";

			echo "<table><tr><td><img src=\"".AVATAR_DIR.( $arr['avatarID'] > 0 ? 'avatar-'.$arr['avatarID'].".".$arr['avatarExtension'] : DEFAULT_AVATAR )."\" alt=\"Avatar\" class=\"faquseravatar\" />";
			echo "</td><td>
			<b>".$arr['username']."</b><br/>";
			if ($arr['userTitle'] != '')
				echo $arr['userTitle']."<br/>";
			echo "<br/><b>Dabei seit:</b> ".tfs(time() - $arr['registrationDate'])."<br/>";
			echo "<a href=\"http://etoa.ch/forum/index.php?page=User&amp;userID=".$arr['userID']."\">Profil im EtoA Forum anzeigen</a>
			
			
			
			</td></tr></table>";
			
			
			$qres = dbquery("
			SELECT
				COUNT(faq_id)
			FROM
				faq
			WHERE
				faq_user_id=".$arr['userID'].";");						
			$qarr = mysql_fetch_row($qres);		
			$questionsAsked = $qarr[0];
			
			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_comments c
			INNER JOIN
				faq f
			ON c.comment_faq_id=f.faq_id
			AND f.faq_user_id=".$arr['userID']."
			AND c.comment_correct=1;");						
			$darr = mysql_fetch_row($dres);
			$answersMarkedCorrect = $darr[0];				
			
			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_vote v
			INNER JOIN
				faq f
			ON f.faq_id=v.faq_id
			AND f.faq_user_id=".$arr['userID']."
			AND v.value=1;");						
			$darr = mysql_fetch_row($dres);
			$questionsVotedPositive = $darr[0];		

			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_vote v
			INNER JOIN
				faq f
			ON f.faq_id=v.faq_id
			AND f.faq_user_id=".$arr['userID']."
			AND v.value=-1;");						
			$darr = mysql_fetch_row($dres);
			$questionsVotedNegative = $darr[0];	
			
			
			$ccres = dbquery("
			SELECT
				COUNT(comment_id),
				SUM(comment_correct)
			FROM
				faq_comments
			WHERE
				comment_user_id=".$arr['userID'].";");						
			$ccarr = mysql_fetch_row($ccres);	
			$answersWritten = $ccarr[0];
			$acceptedAnswers = $ccarr[1];
			
			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_comment_vote v
			INNER JOIN
				faq_comments f
			ON f.comment_id=v.comment_id
			AND f.comment_user_id=".$arr['userID']."
			AND v.value=1;");						
			$darr = mysql_fetch_row($dres);
			$answersVotedPositive = $darr[0];		

			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_comment_vote v
			INNER JOIN
				faq_comments f
			ON f.comment_id=v.comment_id
			AND f.comment_user_id=".$arr['userID']."
			AND v.value=-1;");						
			$darr = mysql_fetch_row($dres);
			$answersVotedNegative = $darr[0];	
			
			
			
			
			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_vote
			WHERE
				user_id=".$arr['userID']."
				AND value=1;");						
			$darr = mysql_fetch_row($dres);
			$questionPostitiveVotes = $darr[0];
			
			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_vote
			WHERE
				user_id=".$arr['userID']."
				AND value=-1;");						
			$darr = mysql_fetch_row($dres);
			$questionNegativeVotes = $darr[0];			
			
			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_comment_vote
			WHERE
				user_id=".$arr['userID']."
				AND value=1;");						
			$darr = mysql_fetch_row($dres);
			$answerPositiveVotes = $darr[0];				
			
			$dres = dbquery("
			SELECT
				COUNT(*)
			FROM
				faq_comment_vote
			WHERE
				user_id=".$arr['userID']."
				AND value=-1;");						
			$darr = mysql_fetch_row($dres);
			$answerNegativeVotes = $darr[0];	


			echo "<h3>Fragen</h3>";
			echo "
			<table><tr><td class=\"faquserprofilebignum\"><a href=\"?page=faq&amp;cat=questions&amp;userid=".$arr['userID']."\">".$questionsAsked."</a></td><td>";
			echo "Als positiv bewertete Fragen: <b>".$questionsVotedPositive."</b><br/>";
			echo "Als negativ bewertete Fragen: <b>".$questionsVotedNegative."</b><br/>";
			echo "Antworten als korrekt akzeptiert: <b>".$answersMarkedCorrect."</b>
			</td></tr></table>";
						
			echo "<h3>Antworten</h3>
			<table><tr><td class=\"faquserprofilebignum\"><a href=\"?page=faq&amp;cat=answers&amp;userid=".$arr['userID']."\">".$answersWritten."</a></td><td>";
			echo "Als positiv bewertete Antworten: <b>".$answersVotedPositive."</b><br/>";
			echo "Als negativ bewertete Antworten: <b>".$answersVotedNegative."</b><br/>";
			echo "Antworten wurden vom Fragesteller als korrekt akzeptiert: <b>".$acceptedAnswers."</b>
			</td></tr></table>";

			echo "<h3>Bewertung von Fragen und Antworten</h3>";
			echo "Fragen positiv bewertet: <b>".$questionPostitiveVotes."</b><br/>
			Fragen negativ bewertet: <b>".$questionNegativeVotes."</b><br/>";
			echo "Antwort positiv bewertet: <b>".$answerPositiveVotes."</b><br/>
			Antwort negativ bewertet: <b>".$answerNegativeVotes."</b>";
			
			echo "<h3>Wiki</h3>";
			$wres = dbquery("SELECT
				COUNT(id)
			FROM
				articles
			WHERE
				user_id='".$arr['userID']."'
				AND rev=1
			;");			
			$warr = mysql_fetch_row($wres);
			$wikiCreated=$warr[0];
			$wres = dbquery("SELECT
				COUNT(id)
			FROM
				articles
			WHERE
				user_id='".$arr['userID']."'
				AND rev>1
			;");				
			$warr = mysql_fetch_row($wres);
			$wikiEdited=$warr[0];
			echo "Artikel begonnen: <b>".$wikiCreated."</b><br/>";
			echo "Bearbeitungen: <b>".$wikiEdited."</b><br/>";
			
			echo "<h3>Tags</h3>";
			$tres = dbquery("SELECT t.id,t.name,COUNT(t.id) as cnt
			FROM help_tag t
			INNER JOIN 
				help_tag_rel r ON t.id=r.tag_id
			INNER JOIN 
				faq f ON r.item_id=f.faq_id
			LEFT JOIN faq_comments a ON a.comment_faq_id=f.faq_id 
			WHERE 
			(comment_user_id=".$arr['userID']."
			OR faq_user_id=".$arr['userID'].")
			GROUP BY t.id
			ORDER BY cnt DESC,t.name
			;");
			while ($tarr = mysql_fetch_assoc($tres))
			{
				echo "<a href=\"?page=tags&amp;id=".$tarr['id']."&amp;userid=".$arr['userID']."\">".$tarr['name']."</a> ".$tarr['cnt']."x<br/>";
			}
		}
		else
		{
			echo message("error","Benutzer nicht gefunden!");
		}
	}
	else
	{
		echo "<h1>Aktive Benutzer</h1>";

		echo "<h2>Fragen & Antworten</h2>";
		$res = dbquery("
		SELECT
			u.*,
			a.avatarExtension,
			a.avatarID
		FROM
			wcf1_user u
		LEFT JOIN
			wcf1_avatar a
			ON u.avatarID=a.avatarID			
		WHERE
			u.userID In ((SELECT DISTINCT faq_user_id FROM faq WHERE faq_user_id>0 UNION SELECT DISTINCT comment_user_id FROM faq_comments WHERE comment_user_id>0))
		GROUP BY
			u.userID
		ORDER BY
			u.username
		;");	
		echo "<table class=\"faquserlist\"><tr><th></th><th>User</th><th>Fragen</th><th>Antworten</th><th>Wiki-Aktivität</th></tr>";		
		while ($arr = mysql_Fetch_assoc($res))
		{
			$qres = dbquery("
			SELECT
				COUNT(faq_id)
			FROM
				faq
			WHERE
				faq_user_id=".$arr['userID'].";");						
			$qarr = mysql_fetch_row($qres);		
			$questionsAsked = $qarr[0];		

			$ccres = dbquery("
			SELECT
				COUNT(comment_id)
			FROM
				faq_comments
			WHERE
				comment_user_id=".$arr['userID'].";");						
			$ccarr = mysql_fetch_row($ccres);	
			$answersWritten = $ccarr[0];
			$wres = dbquery("SELECT
				COUNT(id)
			FROM
				articles
			WHERE
				user_id='".$arr['userID']."'
			;");			
			$warr = mysql_fetch_row($wres);
			$wiki=$warr[0];
			
			echo "<tr>
			<td class=\"avatar\"><img src=\"".AVATAR_DIR.( $arr['avatarID'] > 0 ? 'avatar-'.$arr['avatarID'].".".$arr['avatarExtension'] : DEFAULT_AVATAR )."\" alt=\"Avatar\" /></td>
			<td><a href=\"?page=$page&amp;id=".$arr['userID']."\">".$arr['username']."</a></td>
			<td class=\"num\">".($questionsAsked > 0 ? "<a href=\"?page=faq&amp;cat=questions&amp;userid=".$arr['userID']."\">$questionsAsked</a>" : $questionsAsked)."</td>
			<td class=\"num\">".($answersWritten > 0 ? "<a href=\"?page=faq&amp;cat=answers&amp;userid=".$arr['userID']."\">$answersWritten</a>" : $answersWritten)."</td>
			<td class=\"num\">".$wiki."</td>
			</tr> ";
		}
		echo "</table>";
		//echo message("error","Keine Benutzer-ID angegeben!");
	}
?>