<h1>FAQ Verwaltung</h1>

<?PHP

/********************** 
* Frage bearbeiten    * 
**********************/
if (isset($_GET['faq_edit']) && $_GET['faq_edit']>0)
{
	echo '<b>Frage bearbeiten</b><br/><br/>';
	$res=dbquery("SELECT 
		*
	FROM 
		faq 
	WHERE 
		faq_id=".$_GET['faq_edit'].";");
	if (mysql_num_rows($res)>0)
	{	
		$arr=mysql_fetch_array($res);
	
		if ($_POST['faq_edit_submit']!="")
		{
			if ($_POST['time_reset']==1)
			{
				$add = ",faq_updated=UNIX_TIMESTAMP()";
			}
			
			dbquery("UPDATE
				faq
			SET
				faq_question='".addslashes($_POST['faq_question'])."',
				faq_description='".addslashes($_POST['faq_description'])."'
				".$add."
			WHERE
				faq_id=".$_POST['faq_id'].";");
				

			$sql="DELETE FROM help_tag_rel WHERE domain='faq' AND item_id=".$_POST['faq_id'].";";
			dbquery($sql);
			$tags = explode(",",$_POST['tags']);	
			foreach ($tags as $t)
			{
				$t = trim($t);
				if ($t != "")
				{
					echo "Speichere Tag $t...<br/>";
					$sql="SELECT id FROM help_tag WHERE name='".mysql_real_escape_string($t)."';";
					$res = dbquery($sql);
					if ($idarr = mysql_fetch_row($res))
					{
						$tagId = $idarr[0];
					}					
					else
					{
						$sql="INSERT INTO help_tag (name) VALUES ('".mysql_real_escape_string($t)."');";
						dbquery($sql);
						$tagId = mysql_insert_id();
					}
					$sql="INSERT INTO help_tag_rel (domain,tag_id,item_id) VALUES ('faq',".$tagId.",".$_POST['faq_id'].");";
					dbquery($sql);
				}
			}			
				
			if ($_POST['send_mail']==1)	
			{
				$res=dbquery("SELECT 
					faq_question,
					faq_user_nick,
					faq_user_email
				FROM 
					faq 
				WHERE 
					faq_id=".$_POST['faq_id'].";");
				$arr=mysql_fetch_array($res);		
				$text = "Hallo ".$arr['faq_user_nick']."\n\nDeine Frage \"".stripslashes($arr['faq_question'])."\" wurde beantwortet! Klicke auf den folgenden Link um die Antwort anzuzeigen:\n\nhttp://etoa.ch/?page=faq&faq=".$_POST['faq_id']."";
				$headers .= 'From:EtoA Helpcenter<'.$conf['faq_admin']['v'].">\n";
				$headers .= 'Reply-To:' . $conf['faq_admin']['v'] . "\n"; 
				$headers .= 'X-Mailer: PHP/' . phpversion() . "\n"; 
				$headers .= 'X-Sender-IP: ' . $REMOTE_ADDR . "\n"; 
				mail($arr['faq_user_email'],"EtoA FAQ: Frage beantwortet",$text,$headers);
				echo "Mail gesendet!<br/>";
			}
				
			pushText("Gespeichert!<br/><br/>");
			forwardInternal('index.php?page='.$page.'&show=accepted');
		}

		if (isset($_POST['cancel']))
		{
			forwardInternal('index.php?page='.$page.'&show=accepted');
		}
		
		$sql="SELECT t.id, t.name 
		FROM help_tag t
		INNER JOIN help_tag_rel r
		ON r.tag_id=t.id
		AND r.domain='faq'
		AND r.item_id=".intval($arr['faq_id']).";";
		$tres=dbquery($sql);	
		$tags = array();
		while ($tarr=mysql_fetch_assoc($tres))
		{
			$tags[$tarr['id']] = $tarr['name'];
		}
		$tagstr = implode(",",$tags);			
	
		echo '<form action="?page='.$page.'&amp;faq_edit='.$arr['faq_id'].'" method="post">';
		echo '<table class="tbl">';
		echo '<tr><th>Frage:</th><td><input tzype="text" name="faq_question" size="80" value="'.stripslashes($arr['faq_question']).'"/></td></tr>';
		echo '<tr><th>Beschreibung:</th><td><textarea name="faq_description" cols="60" rows="10">'.stripslashes($arr['faq_description']).'</textarea></td></tr>';
		//echo '<tr><th>Antwort:</th><td><textarea name="faq_answer" cols="60" rows="10">'.stripslashes($arr['faq_answer']).'</textarea></td></tr>';
		//echo '<tr><th>Schl&uuml;sselw&ouml;rter:</th><td><textarea name="faq_keywords" cols="60" rows="4">'.stripslashes($arr['faq_keywords']).'</textarea></td></tr>';
		echo '<tr><th>Tags:</th><td><textarea name="tags" cols="60" rows="4">'.$tagstr.'</textarea></td></tr>';
		if ($arr['faq_user_nick']!="")
		{
			echo '<tr><th>Name:</th><td>'.stripslashes($arr['faq_user_nick']).'</td></tr>';
		}
		if ($arr['faq_user_email']!="")
		{
			echo '<tr><th>E-Mail:</th><td><a href="mailto:'.$arr['faq_user_email'].'">'.$arr['faq_user_email'].'</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="send_mail" value="1" /> Per E-Mail informieren						
			</td></tr>';
		}
		if ($arr['faq_user_ip']!="")
		{
			echo '<tr><th>IP:</th><td>'.$arr['faq_user_ip'].'</td></tr>';
		}
		if ($arr['faq_user_hostname']!="")
		{
			echo '<tr><th>Host:</th><td>'.$arr['faq_user_hostname'].'</td></tr>';
		}
		if ($arr['faq_user_client']!="")
		{
			echo '<tr><th>Client:</th><td>'.$arr['faq_user_client'].'</td></tr>';
		}
		if ($arr['faq_user_host']!="")
		{
			echo '<tr><th>Name:</th><td>'.stripslashes($arr['faq_user_host']).'</td></tr>';
		}
		if ($arr['faq_user_time']>0)
		{
			echo '<tr><th>Zeit:</th><td>'.df($arr['faq_user_time']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="time_reset" value="1" /> Zeit neu setzen			
			</td></tr>';
		}
		echo '<tr><th>Views:</th><td>'.$arr['faq_views'].'</td></tr>';
		if ($arr['faq_ratings']>0)
		{
			echo '<tr><th>Rating:</th><td>'.$arr['faq_rating']/$arr['faq_ratings'].' ('.$arr['faq_ratings'].')</td></tr>';
		}
		echo '</table><br/><input type="submit" name="faq_edit_submit" value="Speichern" /> &nbsp; 
		<input type="submit" name="cancel" value="Abbrechen" /> &nbsp; 
		<input type="button" onclick="document.location=\'http://etoa.ch/help/?page=faq&amp;faq='.$arr['faq_id'].'\'" value="Anzeigen" /> <br/><br/>
		<input type="button" onclick="document.location=\'?page='.$page.'&amp;faq_answers='.$arr['faq_id'].'\'" value="Antworten verwalten" /> &nbsp; 
		<input type="button" onclick="document.location=\'?page='.$page.'&amp;faq_del='.$arr['faq_id'].'\'" value="Löschen" /> &nbsp; 
		';
		
		echo '<input type="hidden" name="faq_id" value="'.$arr['faq_id'].'" /></form>';
	}
	else
	{
		echo "Datensatz nicht gefunden!";
	}
}     

/********************** 
* Frage kommentieren    * 
**********************/
elseif (isset($_GET['faq_answers']) && $_GET['faq_answers']>0)
{
	echo '<h2>Antworten verwalten</h2>';
	
	
	
	$res=dbquery("SELECT 
		*
	FROM 
		faq 
	WHERE 
		faq_id=".$_GET['faq_answers'].";");
	if (mysql_num_rows($res)>0)
	{	
		$arr=mysql_fetch_array($res);
		echo "<b>".stripslashes($arr['faq_question'])."</b><br/><br/>";
		echo '<form action="?page='.$page.'&amp;faq_comments='.$arr['faq_id'].'" method="post">';
		
		if ($_GET['delcmt']>0)
		{
			print("DELETE FROM
			faq_comments
			WHERE
			comment_id=".$_GET['delcmt']."");
			echo "<p>Antwort gelöscht!</p>";
		}
		
		$cres = dbquery("
		SELECT
			*
		FROM
			faq_comments
		WHERE
			comment_faq_id=".$arr['faq_id']."
		");
		if (mysql_num_rows($cres)>0)
		{
			echo "<table class=\"tbl\" style=\"width:100%\"><tr>
				<th class=\"tbldata\">User</th>
				<th class=\"tbldata\">E-Mail</th>
				<th class=\"tbldata\">Text</th>
				<th class=\"tbldata\">Zeit</th>
				<th class=\"tbldata\">Optionen</th>
			</tr>";
			while ($carr=mysql_fetch_array($cres))
			{
				echo "<tr>
					<td class=\"tbldata\">".$carr['comment_nick']."</td>
					<td class=\"tbldata\"><a href=\"mailto:".$carr['comment_email']."\">".$carr['comment_email']."</a></td>
					<td class=\"tbldata\">".text2html($carr['comment_text'])."</td>
					<td class=\"tbldata\">".df($carr['comment_time'])."</td>
					<td class=\"tbldata\"><a href=\"?page=$page&faq_answers=".$arr['faq_id']."&amp;delcmt=".$carr['comment_id']."\">Löschen</a></td>					
				</tr>";
			}
			echo "</table><br/>";
		}
		else
		{
			echo "<i>Keine Kommentare vorhaden!</i><br/><br/>";
		}
		//echo '<br/><input type="submit" name="faq_edit_submit" value="Speichern" /> &nbsp; ';
		
		echo '<input type="button" onclick="document.location=\'?page='.$page.'&amp;faq_edit='.$arr['faq_id'].'\'" value="Zurück zur Frage" /> &nbsp; ';
		echo "<input type=\"button\" onclick=\"document.location='?page=$page&amp;show=accepted';\" value=\"Übersicht\" /> ";
		echo '<input type="hidden" name="faq_id" value="'.$arr['faq_id'].'" /></form>';
	}
	else
	{
		echo "Datensatz nicht gefunden!";
	}
}   

/********************** 
* Frage löschen       * 
**********************/
elseif (isset($_GET['faq_del']) && $_GET['faq_del']>0)
{
	echo '<b>Frage l&ouml;schen</b><br/><br/>';
	$res=dbquery("SELECT 
		*
	FROM 
		faq 
	WHERE 
		faq_id=".$_GET['faq_del'].";");
	if (mysql_num_rows($res)>0)
	{	
		$arr=mysql_fetch_array($res);
		
		if ($_POST['faq_del_submit']!="")
		{
			dbquery("UPDATE
				faq
			SET
				faq_deleted=1
			WHERE
				faq_id=".$_POST['faq_id'].";");
			pushText("Gel&ouml;scht!<br/><br/>");
			forwardInternal('index.php?page='.$page.'&show=accepted');
		}			
			
		
		echo '<form action="?page='.$page.'&amp;faq_del='.$_GET['faq_del'].'" method="post">';
		echo 'Soll folgende Frage gel&ouml;scht werden?<br/><br/>';
		echo text2html($arr['faq_question']);
		echo '<br/><br/><input type="submit" name="faq_del_submit" value="Ja" />
		<input type="button" onclick="document.location=\'?page='.$page.'&amp;faq_edit='.$arr['faq_id'].'\'" value="Abbrechen" /> &nbsp; ';
		echo '<input type="hidden" name="faq_id" value="'.$arr['faq_id'].'" /></form>';
	}
	else
	{
		echo "Datensatz nicht gefunden!";
	}
}     

/**********************
* Kategorie anzeigen  *
**********************/
elseif (isset($_GET['show']) && $_GET['show']=="accepted")
{

		
	echo '<h2>Eingetragene Fragen</h2>';

	echo '<p><input type="button" value="Neue Frage" onclick="document.location=\'/help?page=faq&amp;cat=submit\'" /> &nbsp; 
	<input type="button" value="Zur &Uuml;bersicht" onclick="document.location=\'?page='.$page.'\'" /></p>';

	
	echo popText();
	
	$res=dbquery("SELECT 
		faq_id,
		faq_question,
		faq_views,
		faq_vote
	FROM 
		faq 
	WHERE 
		faq_deleted=0
	ORDER BY
		faq_user_time DESC;");
	if (mysql_num_rows($res)>0)
	{
		echo '<table class="tbl">';
		echo '<tr><th>Frage</th>
		<th>Antworten</th>
		<th>Tags</th>
		<th>Wertung</th>
		</tr>';
		while ($arr=mysql_fetch_array($res))
		{
			$ccres = dbquery("
				SELECT
					COUNT(comment_id)
				FROM
					faq_comments
				WHERE
					comment_faq_id=".$arr['faq_id']."
				");
			$ccarr = mysql_fetch_row($ccres);		
			$sql="SELECT COUNT(t.id)
				FROM help_tag t
				INNER JOIN help_tag_rel r
				ON r.tag_id=t.id
				AND r.domain='faq'
				AND r.item_id=".intval($arr['faq_id']).";";
			$tres=dbquery($sql);	
			$tarr = mysql_fetch_row($tres);	
			
			echo '<tr><td><a href="?page='.$page.'&amp;faq_edit='.$arr['faq_id'].'">'.stripslashes($arr['faq_question']).'</a></td>
			<td style="text-align:center;'.($ccarr[0] == 0 ? 'font-weight:bold;color:#A52211;' : '').'">'.$ccarr[0].'</td>
			<td style="text-align:center;'.($tarr[0] == 0 ? 'font-weight:bold;color:#A52211;' : '').'">'.$tarr[0].'</td>
			<td style="text-align:center;'.($arr['faq_vote'] < 0 ? 'font-weight:bold;color:#A52211;' : '').'">'.$arr['faq_vote'].'</td>
			</tr>';
		}		
		echo '</table><br/>';	
	}
	else
	{
		echo "Keine Fragen vorhanden!<br/><br/>";
	}
	

	
}

/*************************
* Papierkorb anzeigen  *
**************************/
elseif (isset($_GET['show']) && $_GET['show']=="deleted")
{
	if ($_GET['deldel']>0)
	{
		dbquery("DELETE FROM
			faq
		WHERE
			faq_id=".$_GET['deldel'].";");
		echo "Gel&ouml;scht!<br/><br/>";
	}
	if ($_GET['undel']>0)
	{
		dbquery("UPDATE
			faq
		SET
			faq_deleted=0
		WHERE
			faq_id=".$_GET['undel'].";");
		echo "Wiederhergestellt!<br/><br/>";
	}	

	echo '<b>Gel&ouml;schte Fragen</b><br/><br/>';
	$res=dbquery("SELECT 
		faq_id,
		faq_question
	FROM 
		faq 
	WHERE 
		faq_deleted=1
	ORDER BY
		faq_question;");
	if (mysql_num_rows($res)>0)
	{
		echo '<table class="tbl">';
		echo '<tr><th>Frage</th><th>Aktionen</th></tr>';
		while ($arr=mysql_fetch_array($res))
		{
			echo '<tr><td>'.$arr['faq_question'].'</td>
			<td><a href="?page='.$page.'&amp;show=deleted&amp;undel='.$arr['faq_id'].'">Wiederherstellen</a> &nbsp;
			<a href="?page='.$page.'&amp;show=deleted&amp;deldel='.$arr['faq_id'].'">L&ouml;schen </a></td></tr>';
		}		
		echo '</table><br/>';	
	}
	else
	{
		echo "Keine Fragen vorhanden!<br/><br/>";
	}
	echo '<input type="button" value="Zur &Uuml;bersicht" onclick="document.location=\'?page='.$page.'\'" /> &nbsp;';
	echo '<input type="button" value="Papierkorb leeren" onclick="document.location=\'?page='.$page.'&amp;action=flushbin\'" />';	
}

/********************** 
* Übersicht           *
**********************/
else
{
	if (isset($_GET['action']) && $_GET['action']=="flushbin")
	{
		$res=dbquery("
		DELETE FROM 
			faq 
		WHERE 
			faq_deleted=1
		;");		
		echo mysql_affected_rows()." Datensätze gelöscht!<br/><br/>";
	}
	
	// Accepted
	echo "<br/>";
	echo '<table class="tbl">';
	echo '<tr><th style="width:150px;">Kategorie</th><th>Fragen</th></tr>';

	$ures=dbquery("
	SELECT
		COUNT(*) as cnt
	FROM
		faq
	WHERE
		faq_deleted=0
	;");                                   
	$uarr=mysql_fetch_array($ures);		
	echo '<tr><td><a href="?page='.$page.'&amp;show=accepted">Eingetragene Fragen</a></td>';
	echo '<td>'.$uarr['cnt'].'</td></tr>';
	
	// Deleted
	$ures=dbquery("
	SELECT
		COUNT(*) as cnt
	FROM
		faq
	WHERE
		faq_deleted=1;
	");                                   
	$uarr=mysql_fetch_array($ures);
	if ($uarr['cnt']>0)
	{
		echo '<tr><td><a href="?page='.$page.'&amp;show=deleted">Gel&ouml;schte Fragen</a></td>';
		echo '<td>'.$uarr['cnt'].'</td></tr>';		
	}
	echo '</table><br/>';
}

?>