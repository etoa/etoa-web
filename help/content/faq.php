<div id="innercontent">
<h1>Häufig gestellte Fragen (FAQ)</h1>
<?PHP

	$rulesText = "<b>Regeln:</b> Keine Namen von Mitspielern im Text, keine Koordinaten, kein Spam, kein Fluchwörter, keine Werbung. 
			Es gelten dieselben <a href=\"http://etoa.ch/forum/index.php?page=Board&boardID=10\">Regeln</a> wie im Forum (z.B. betreffend illegaler Inhalte) sowie die allgemeine Nettiquette. 
			Missbrauch dieser Funktion kann zu einer Sperre im Forum und/oder im Spiel selbst führen. 
			Mit dem Absenden erklärst du dich einverstanden, dass deine Foren-Accountdaten (Username, E-Mail, Benutzer-ID) mit der Frage gespeichert werden. 
			Um Missbrauchsfälle aufzudecken wird auch deine IP-Adresse sowie der verwendete Browser aufgezeichnet.";

	$submitInfoText = "<b>Wichtig:</b> Nur eine Frage auf einmal stellen! Beschreibe den Sachverhalt möglichst präzise, versuche dabei aber den Text kurz zu halten. Bitte keine Fehlerberichte wie 'Login geht nicht...', diese bitte ins <a href=\"http://www.etoa.ch/forum/index.php?page=Board&boardID=21\">Support-Forum</a> stellen.";

	$requireLoginMsg = message("warning","Du bist nicht eingeloggt! Bitte logge dich <a href=\"login\">hier</a> mit deinem Forum-Account ein!");
	
	$cats = array(
		"updated"=>"Aktualisiert",
		"newest"=>"Neu",
		"unanswered"=>"Unbeantwortet",
		"views"=>"Views",
		"rating"=>"Bewertung",
		"my"=>"Meine Fragen",
		"submit"=>"Frage stellen");
	$cat = isset($_GET['cat']) && (isset($cats[$_GET['cat']]) || $_GET['cat']=="questions" || $_GET['cat']=="answers") ? $_GET['cat']  :  "updated";

	echo "<ul id=\"faqmenu\">";
	foreach($cats as $k => $v)
	{
		echo "<li><a href=\"?page=$page&amp;cat=".$k."\"".($cat==$k && !isset($_GET['faq']) && !isset($_GET['editfaq']) ? ' class="current"' : '').">".$v."</a></li>";
	}
	echo "</ul>";

	if (count($_POST)==0)
	{
		genfkey();
	}

	/****************
	* Vote question *
	*****************/
	if (isset($_GET['votefaq']) && $_GET['votefaq'] > 0 && isset($_GET['vote']))
	{
		if (LOGIN)
		{
			$res = dbquery("SELECT value
			FROM faq_vote 
			WHERE 
				faq_id=".$_GET['votefaq']."
				AND user_id=".$_SESSION['etoahelp']['uid']."
			;");			
			if ($arr = mysql_fetch_row($res))
			{
				if ($_GET['vote'] == "up" && $arr[0]==1 || $_GET['vote'] == "down" && $arr[0]==-1)
				{
					dbquery("DELETE FROM faq_vote 
					WHERE 
						faq_id=".$_GET['votefaq']."
						AND user_id=".$_SESSION['etoahelp']['uid']."
					;");			
					dbquery("UPDATE faq
					SET 
						faq_vote=faq_vote+(".($_GET['vote'] == "up" ? -1 : 1)."),
						faq_updated=UNIX_TIMESTAMP()
					WHERE faq_id=".$_GET['votefaq'].";");
				}
				else
				{
					dbquery("UPDATE faq_vote 
					SET 
						value=".(-$arr[0])."
					WHERE 
						faq_id=".$_GET['votefaq']."
						AND user_id=".$_SESSION['etoahelp']['uid']."
					;");			
					dbquery("UPDATE faq
					SET 
						faq_vote=faq_vote+(".(-(2*$arr[0]))."),
						faq_updated=UNIX_TIMESTAMP()
					WHERE faq_id=".$_GET['votefaq'].";");				
				}
			}
			else
			{
				$v = $_GET['vote'] == "up" ? 1 : -1;
				dbquery("INSERT INTO faq_vote 
				(faq_id,user_id,value,timestamp) 
				VALUES(".$_GET['votefaq'].",".$_SESSION['etoahelp']['uid'].",".$v.",UNIX_TIMESTAMP())
				ON DUPLICATE KEY UPDATE value=".$v.";");
				if (mysql_affected_rows() > 0 )
				{
					dbquery("UPDATE faq
					SET 
						faq_vote=faq_vote+(".$v."),
						faq_updated=UNIX_TIMESTAMP()
					WHERE faq_id=".$_GET['votefaq'].";");
				}
			}
			forwardInternal("?page=$page&faq=".$_GET['votefaq']);
		}
		else
		{
			echo $requireLoginMsg;
		}
	}
	
	/**************
	* Vote answer *
	***************/
	elseif (isset($_GET['voteanswer']) && $_GET['voteanswer'] > 0 && isset($_GET['vote']))
	{
		if (LOGIN)
		{
			$res = dbquery("SELECT comment_faq_id 
			FROM faq_comments
			WHERE comment_id=".$_GET['voteanswer'].";");
			if ($arr = mysql_fetch_row($res))
			{
				$faqId = $arr[0];
			
				$res = dbquery("SELECT value
				FROM faq_comment_vote 
				WHERE 
					comment_id=".$_GET['voteanswer']."
					AND user_id=".$_SESSION['etoahelp']['uid']."
				;");			
				if ($arr = mysql_fetch_row($res))
				{
					if ($_GET['vote'] == "up" && $arr[0]==1 || $_GET['vote'] == "down" && $arr[0]==-1)
					{
						dbquery("DELETE FROM faq_comment_vote 
						WHERE 
							comment_id=".$_GET['voteanswer']."
							AND user_id=".$_SESSION['etoahelp']['uid']."
						;");			
						dbquery("UPDATE faq_comments
						SET comment_vote=comment_vote+(".($_GET['vote'] == "up" ? -1 : 1).")
						WHERE comment_id=".$_GET['voteanswer'].";");
					}
					else
					{
						dbquery("UPDATE faq_comment_vote 
						SET 
							value=".(-$arr[0])."
						WHERE 
							comment_id=".$_GET['voteanswer']."
							AND user_id=".$_SESSION['etoahelp']['uid']."
						;");			
						dbquery("UPDATE faq_comments
						SET comment_vote=comment_vote+(".(-(2*$arr[0])).")
						WHERE comment_id=".$_GET['voteanswer'].";");				
					}
				}
				else
				{
					$v = $_GET['vote'] == "up" ? 1 : -1;
					dbquery("INSERT INTO faq_comment_vote 
					(comment_id,user_id,value,timestamp) 
					VALUES(".$_GET['voteanswer'].",".$_SESSION['etoahelp']['uid'].",".$v.",UNIX_TIMESTAMP())
					ON DUPLICATE KEY UPDATE value=".$v.";");
					if (mysql_affected_rows() > 0 )
					{
						dbquery("UPDATE faq_comments
						SET comment_vote=comment_vote+(".$v.")
						WHERE comment_id=".$_GET['voteanswer'].";"); 	//comment_faq_id
					}
				}				
				
				dbquery("UPDATE faq
				SET faq_updated=UNIX_TIMESTAMP()
				WHERE faq_id=".$faqId.";");					

				forwardInternal("?page=$page&faq=".$faqId);
			}
			else
			{
				echo message("error","Diese Antwort existiert nicht!");
			}
		}
		else
		{
			echo $requireLoginMsg;
		}
	}	
	
	/**********************
	* Mark correct answer *
	**********************/
	elseif (isset($_GET['acceptanswer']) && $_GET['acceptanswer'] > 0 )
	{
		if (LOGIN)
		{
			$res = dbquery("SELECT comment_faq_id,comment_correct 
			FROM faq_comments
			WHERE comment_id=".$_GET['acceptanswer'].";");
			if ($arr = mysql_fetch_row($res))
			{
				$faqId = $arr[0];
				$alreadyCorrect = $arr[1];
				$faqLink = "?page=$page&faq=".$faqId;

				$res = dbquery("SELECT faq_id 
				FROM faq
				WHERE faq_id=".$faqId."
				AND faq_user_id=".$_SESSION['etoahelp']['uid'].";");
				if (mysql_fetch_row($res))
				{	
					dbquery("UPDATE faq_comments
					SET comment_correct=0
					WHERE comment_faq_id=".$faqId.";");	
					
					if ($alreadyCorrect == 0)
					{
						dbquery("UPDATE faq_comments
						SET comment_correct=1
						WHERE comment_id=".$_GET['acceptanswer'].";");							
					}
				}
				else
				{
					echo message("error","Du kannst nur Antworten auf eigene Fragen akzeptieren!");
					echo "<p><a href=\"$faqLink\">Zurück zur Frage</a></p>";
				}
				
				dbquery("UPDATE faq
				SET faq_updated=UNIX_TIMESTAMP()
				WHERE faq_id=".$faqId.";");					
				
				forwardInternal($faqLink);
			}
			else
			{
				echo message("error","Diese Antwort existiert nicht!");
			}
		}
		else
		{
			echo $requireLoginMsg;
		}
	}	
	
	/************************
	* Show question details *
	************************/
	elseif (isset($_GET['faq']) && $_GET['faq']>0)
	{
		$res=dbquery("
		SELECT
			*
		FROM 
			faq
		WHERE
			faq_deleted=0		
			AND faq_id=".intval($_GET['faq']).";
		");
		if (mysql_num_rows($res)>0)
		{
			// Answer
			$arr=mysql_fetch_array($res);
			echo "<h2 class=\"faqquestiontitle\">".text2html($arr['faq_question'])."</h2>";
			echo popText();
			
			echo "<div class=\"questiontextbox\">";
			
			$upImg = "up.png";
			$downImg = "down.png";
			if (LOGIN)
			{
				$vres = dbquery("SELECT value
				FROM faq_vote 
				WHERE 
					faq_id=".$arr['faq_id']."
					AND user_id=".$_SESSION['etoahelp']['uid']."
				;");
				if ($varr = mysql_fetch_row($vres))
				{
					if ($varr[0]==1)
						$upImg = "up_blue.png";
					elseif ($varr[0]==-1)
						$downImg = "down_blue.png";
						
				}
			}
				
			echo "<div class=\"faqquestionvote\">
			<a href=\"?page=$page&amp;votefaq=".$arr['faq_id']."&amp;vote=up\" title=\"Diese Frage ist sinnvoll und klar formuliert (nochmals klicken um Wahl rückgängig zu machen)\">
			<img src=\"web/images/$upImg\" src=\"Up\"/></a>
			<div class=\"faqvotes\">".$arr['faq_vote']."</div>
			<a href=\"?page=$page&amp;votefaq=".$arr['faq_id']."&amp;vote=down\" title=\"Diese Frage ist nicht sinnvoll oder unklar (nochmals klicken um Wahl rückgängig zu machen)\">
			<img src=\"web/images/$downImg\" src=\"Down\"/></a>";
			echo "</div>
			<div class=\"faqquestiondesc\">
			<div class=\"faqquestiondesc\">".text2html($arr['faq_description'])."</div>";
			$author = $arr['faq_user_nick'] != "" && $arr['faq_user_id']>0 ? "<a href=\"?page=user&id=".$arr['faq_user_id']."\">".stripslashes($arr['faq_user_nick'])."</a>"  : "Unbekannt";
			$timestr = $arr['faq_user_time'] > 0 ? ", ".tfs(time() - $arr['faq_user_time']) : ", vor langer Zeit";
			echo "<div class=\"faqanswerinfo\"><span class=\"answerinfo_tools\">";
			if ($arr['faq_user_id'] == $_SESSION['etoahelp']['uid'])
			{
				echo "<a href=\"?page=$page&amp;editfaq=".$arr['faq_id']."\">bearbeiten</a>";
			}
			echo "</span><span class=\"answerinfo_author\">".$author.$timestr."</span>";
			echo "<br class=\"clearer\"/></div>";
			echo "</div>";			
			echo "<br class=\"clearer\" />";

			
			if (isset($_POST[encfname('comment_submit')]))
			{
				if ($_POST[encfname('comment_text')]!="")
				{
					$username =  $_SESSION['etoahelp']['username']; //addslashes($_POST[encfname('comment_nick')]);
					$email =  $_SESSION['etoahelp']['email'];	//addslashes($_POST[encfname('comment_email')]);
					$userid =  $_SESSION['etoahelp']['uid'];
					dbquery("
					INSERT INTO
						faq_comments
					(
						comment_time,
						comment_user_id,
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
						'".$userid."',
						'".$username."',
						'".$email."',
						'".addslashes($_POST[encfname('comment_text')])."',
						'".$_SERVER['HTTP_USER_AGENT']."',
						'".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',						
						'".$_SERVER['REMOTE_ADDR']."',
						".$arr['faq_id']."
					);");				
					$aid = mysql_insert_id();
					
					dbquery("UPDATE faq
					SET faq_updated=UNIX_TIMESTAMP()
					WHERE faq_id=".$arr['faq_id'].";");					
					
					echo message("success","Vielen Dank für deine Antwort!");
					
					$text = $username." hat eine neue Antwort zu deiner Frage\n\n'".$arr['faq_question']."'\n\nin der EtoA-Hilfe geschrieben";
					//$text .= "\n\nUser-Agent: ".$_SERVER['HTTP_USER_AGENT']."\nHost: ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."";
					$text .= "\n\nhttp://www.etoa.ch/help/?page=faq&faq=".$arr['faq_id']."";
					mail($arr['faq_user_email'],"EtoA-FAQ: Neuer Antwort zu deiner Frage",$text);
				}
				else
				{
					echo message("error","Fehler! Nicht alle Felder ausgefüllt!")	;
				}
				genfkey();
			}
			else
			{
				genfkey();
			}
						
			
			$cres = dbquery("
			SELECT
				comment_id,
				comment_text,
				comment_nick,
				comment_user_id,
				comment_time,
				comment_vote,
				comment_correct
			FROM
				faq_comments
			WHERE
				comment_faq_id=".$arr['faq_id']."
			ORDER BY
				comment_time ASC;
			");
			$nr = mysql_num_rows($cres);
			if ($nr>0)
			{
				echo "<h2>$nr ".($nr > 1 ? "Antworten" : "Antwort")."</h2>";
				while ($carr=mysql_fetch_array($cres))
				{				
					$upImg = "up.png";
					$downImg = "down.png";
					if (LOGIN)
					{
						$vres = dbquery("SELECT value
						FROM faq_comment_vote 
						WHERE 
							comment_id=".$carr['comment_id']."
							AND user_id=".$_SESSION['etoahelp']['uid']."
						;");
						if ($varr = mysql_fetch_row($vres))
						{
							if ($varr[0]==1)
								$upImg = "up_blue.png";
							elseif ($varr[0]==-1)
								$downImg = "down_blue.png";
								
						}
					}								
				
					$answerer = $carr['comment_nick'] != "" ? ($carr['comment_user_id']>0 ? "<a href=\"?page=user&id=".$carr['comment_user_id']."\">".stripslashes($carr['comment_nick'])."</a>" : stripslashes($carr['comment_nick']) ) : "EtoA-Team";
					$timestr = $carr['comment_time'] > 0 ? ", ".tfs(time() - $carr['comment_time']) : ", vor langer Zeit";
					echo "<div class=\"faqanswercontainer\">
					<div class=\"faqquestionvote\">
					<a href=\"?page=$page&amp;voteanswer=".$carr['comment_id']."&amp;vote=up\" title=\"Diese Antwort ist hilfreich (nochmals klicken um Wahl rückgängig zu machen)\">
					<img src=\"web/images/$upImg\" src=\"Up\"/></a>
					<div class=\"faqvotes\">".$carr['comment_vote']."</div>
					<a href=\"?page=$page&amp;voteanswer=".$carr['comment_id']."&amp;vote=down\" title=\"Diese Antwort ist nicht hilfreich (nochmals klicken um Wahl rückgängig zu machen)\">
					<img src=\"web/images/$downImg\" src=\"Down\"/></a>";
					
					if ($carr['comment_correct']==1)
					{
						$correctImg = "correct.png";
						$correctTitle = "Der Fragesteller akzeptierte dies als die beste Antwort (nochmals klicken um Wahl rückgängig zu machen)";
					}
					elseif (LOGIN && $arr['faq_user_id']==$_SESSION['etoahelp']['uid'] && $carr['comment_correct']==0)
					{
						$correctImg = "correct_vote.png";
						$correctTitle = "Klicke hier um dies als die beste Antwort zu akzeptieren";
					}					
					if (isset($correctImg))
					{
						echo "<br/>";
						echo "<a href=\"?page=$page&amp;acceptanswer=".$carr['comment_id']."\" title=\"$correctTitle\">
						<img src=\"web/images/$correctImg\" src=\"Correct\"/></a>";					
					}
						
					echo "</div>";
					echo "<div class=\"faqanswer\">
					<div class=\"faqquestiondesc\">".text2html($carr['comment_text'])."</div>";
					echo "<div class=\"faqanswerinfo\">";
					echo "<span class=\"answerinfo_tools\">";
					if ($carr['comment_user_id'] == $_SESSION['etoahelp']['uid'])
					{
						echo "<a href=\"?page=$page&amp;editanswer=".$carr['comment_id']."\">bearbeiten</a>";
					}
					echo "</span><span class=\"answerinfo_author\">".$answerer.$timestr."</span>";					
					echo "</div>";
					echo "</div>";
					echo "<br class=\"clearer\" />
					</div>";
				}
			}
			else
			{
				echo message("warning","Noch keine Antwort vorhanden!");
			}				
			
			echo "<h3>Deine Antwort:</h3>";
			if (!LOGIN)
			{
				echo $requireLoginMsg;
			}
			else
			{
				echo "<div id=\"submitForm\"><form action=\"?page=$page&amp;faq=".$arr['faq_id']."\" method=\"post\">";
				echo "<p><textarea name=\"".encfname('comment_text')."\" rows=\"5\" cols=\"60\"></textarea></p>";
				echo "<p><td><input type=\"submit\" name=\"".encfname('comment_submit_fake')."\" value=\"Einsenden\" style=\"display:none;\" />
						<input type=\"submit\" name=\"".encfname('comment_submit')."\" value=\"Einsenden\" /></p>";
				echo message("info",$rulesText);
				echo "</form></div>";			
			}
				
			echo "</div><div class=\"questiondetailsbox\">";
			
		
			
			// Infos
			echo "<h3>Infos</h3>";
			echo "<p><b>Aufrufe:</b> ".$arr['faq_views']."</p>";

			$sql="SELECT t.id, t.name 
			FROM help_tag t
			INNER JOIN help_tag_rel r
			ON r.tag_id=t.id
			AND r.domain='faq'
			AND r.item_id=".intval($_GET['faq']).";";
			$tres=dbquery($sql);	
			if (mysql_num_rows($tres) > 0)
			{
				echo "<div class=\"questiontags\"><h3>Tags</h3> ";
				$tags = array();
				while ($tarr=mysql_fetch_assoc($tres))
				{
					echo "<a href=\"?page=tags&amp;id=".$tarr['id']."\">".$tarr['name']."</a>  ";
					$tags[$tarr['id']] = $tarr['name'];
				}			
				echo "</div>";
			}

			dbquery("UPDATE faq SET faq_views=faq_views+1 WHERE faq_id=".$arr['faq_id'].";");

			// Similar
			$faqs = array();
			foreach ($tags as $k => $v)
			{
				$sql="SELECT i.faq_id
				FROM help_tag_rel r
				INNER JOIN faq i
				ON r.item_id=i.faq_id
				AND r.tag_id=".$k."
				AND r.domain='faq' 
				AND i.faq_id!=".$_GET['faq'].";";
				$tres=dbquery($sql);
				while ($tarr=mysql_fetch_assoc($tres))
				{
					if (!isset($faqs[$tarr['faq_id']]))
						$faqs[$tarr['faq_id']] = 1;
					else
						$faqs[$tarr['faq_id']]++;
				}
			}
			
			if (count($faqs) > 0)
			{
				asort($faqs);
				echo "<h3>Ähnliche Fragen</h3><ul class=\"similarquestions\">";
				$i = 0;
				foreach ($faqs as $k => $v)
				{
					$sres = dbquery("
					SELECT 	*
					FROM faq
					WHERE faq_id=$k;");
					if (mysql_num_rows($sres)>0)
					{
						$sarr = mysql_fetch_array($sres);
						echo "<li><a href=\"?page=$page&amp;faq=".$sarr['faq_id']."\">
						".text2html($sarr['faq_question'])."</a></li>";
					}	
					if (++$i==5)
						break;
				}			
				echo "</ul>";			
			}
			echo "<p><br/><br/><input type=\"button\" onclick=\"document.location='http://www.etoa.ch/admin/?page=faq&faq_edit=".$arr['faq_id']."'\" value=\"Admin: Bearbeiten\" /></p>";

			echo "</div>";			
			echo "<br class=\"clearer\" />";
			
		}	
		else
		{
			echo "Frage nicht vorhanden!";
		}		
		/*echo "<br/>";
		<input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"Zur&uuml;ck zu den Fragen\" /> &nbsp; */
	}	
	
	
	/********
	* Suche *
	********/
	elseif (($_POST['faq_search']!="" && ($_POST['faq_search_keyword']!="" || $_POST['faq_search_fulltext']!="")) || $_GET['s']!="")
	{
		//echo "<h2>Suche</h2>";
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
				faq_description LIKE '%".$s."%'
				OR faq_question LIKE '%".$s."%'
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
			echo '<p>Suchergebnisse f&uuml;r <b>'.$_POST['faq_search'].'</b>:</p>';
			echo "<ul>";
			while ($arr=mysql_fetch_array($res))
			{
				echo "<li><a href=\"?page=$page&amp;faq=".$arr['faq_id']."\">".$arr['faq_question']."</a></li>";
			}
			echo '</ul><br/>';
		}
		else
		{
			echo "<i>Deine Suche nach <b>".$_POST['faq_search']."</b> ergab keine Treffer!</i><br/><br/>";
		}
		//echo '<input type="button" value="Zur &Uuml;bersicht" onclick="document.location=\'?page='.$page.'\'" />';
	}	

	/*************
	* Antwort bearbeiten *
	*************/	
	elseif (isset($_GET['editanswer']) && $_GET['editanswer'] > 0)
	{
		$res=dbquery("
		SELECT
			*
		FROM 
			faq_comments
		WHERE 
			comment_id=".intval($_GET['editanswer']).";
		");
		if (mysql_num_rows($res)>0)
		{
			$arr = mysql_fetch_assoc($res);
			$id = $_GET['editanswer'];
			
			if (LOGIN && $arr['comment_user_id'] == $_SESSION['etoahelp']['uid'])
			{
				if ($_POST[encfname('faq_user_question_submit')]!="")
				{
					if ($_POST[encfname('comment_text')]!="")
					{
						dbquery("UPDATE
							faq_comments
						SET
							comment_text='".addslashes($_POST[encfname('comment_text')])."',
							comment_ip='".$_SERVER['REMOTE_ADDR']."',
							comment_host='".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',
							comment_client='".$_SERVER['HTTP_USER_AGENT']."',
							comment_updated=UNIX_TIMESTAMP()
						WHERE
							comment_id=".$id.";");
						
						pushText(message('success','Vielen Dank, deine Antwort wurde gespeichert!'));
						forwardInternal("?page=faq&faq=".$arr['comment_faq_id']);

					}
					else
					{
						echo message('error','Du hast keinen Text eingegeben!');
					}
					genfkey();
				}
				genfkey();

				echo "<h3>Antwort bearbeiten</h3>";
	
				echo '<form action="?page='.$page.'&amp;editanswer='.$id.'" method="post">';
		
				echo '<p><textarea name="'.encfname('comment_text').'" rows="8" cols="90">'.$arr['comment_text'].'</textarea></p>';
			
				echo '<input type="submit" name="'.encfname('faq_user_question_submit').'" value="Speichern" /> &nbsp; ';
				echo '<a href="?page='.$page.'&amp;faq='.$arr['comment_faq_id'].'">Abbrechen</a>';
				echo '</form>';

			}
			elseif (!LOGIN)
			{
					echo $requireLoginMsg;
			}				
			else
			{
				echo message("error","Du hast keine Berechtigung um diese Frage zu bearbeiten!");
			}		
		}		
		else
		{
			echo message('error','Diese Frage existiert nicht!');
		}
	}		
	

	/*************
	* Frage bearbeiten *
	*************/	
	elseif (isset($_GET['editfaq']) && $_GET['editfaq'] > 0)
	{
		$res=dbquery("
		SELECT
			*
		FROM 
			faq
		WHERE 
			faq_id=".intval($_GET['editfaq']).";
		");
		if (mysql_num_rows($res)>0)
		{
			$arr = mysql_fetch_assoc($res);
			$id = $_GET['editfaq'];
			
			if (LOGIN && $arr['faq_user_id'] == $_SESSION['etoahelp']['uid'])
			{
				if ($_POST[encfname('faq_user_question_submit')]!="")
				{
					if ($_POST[encfname('faq_user_question')]!="" && $_POST[encfname('faq_description')] !="")
					{
						dbquery("UPDATE
							faq
						SET
							faq_question='".addslashes($_POST[encfname('faq_user_question')])."',
							faq_description='".addslashes($_POST[encfname('faq_description')])."',
							faq_updated=UNIX_TIMESTAMP(),
							faq_user_ip='".$_SERVER['REMOTE_ADDR']."',
							faq_user_host='".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',
							faq_user_client='".$_SERVER['HTTP_USER_AGENT']."'
						WHERE
							faq_id=".$id.";");
						
						$tags = explode(",",$_POST[encfname('tags')]);
						dbquery("DELETE FROM help_tag_rel WHERE domain='faq' AND item_id=".$id.";");
						foreach ($tags as $t)
						{
							$t = trim($t);
							if ($t != "")
							{
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
								$sql="INSERT INTO help_tag_rel (domain,tag_id,item_id) VALUES ('faq',".$tagId.",".$id.");";
								dbquery($sql);
							}
						}
												
						$url = "?page=faq&faq=".$id;
						pushText(message('success','Vielen Dank, deine Frage wurde gespeichert!'));
						forwardInternal($url);

					}
					else
					{
						echo message('error','Du hast keinen Text eingegeben!');
					}
					genfkey();
				}
				genfkey();

				echo "<h3>Frage bearbeiten</h3>";
		
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

		
				echo '<form action="?page='.$page.'&amp;editfaq='.$arr['faq_id'].'" method="post">';
		
				echo '<p><label>Titel:</label><br/>
				<input type="text" name="'.encfname('faq_user_question').'" size="100" value="'.$arr['faq_question'].'" /></p>
				<p><label>Beschreibung:</label><br/>
				<textarea name="'.encfname('faq_description').'" rows="8" cols="90">'.$arr['faq_description'].'</textarea></p>';
				echo '<p><label>Tags (mit Komma trennen; z.B. Schiffe,Preis,Geschwindigkeit):</label><br/>
				<input type="text" name="'.encfname('tags').'" size="100" value="'.$tagstr.'"/></p>';
			
				echo '<input type="submit" name="'.encfname('faq_user_question_submit').'" value="Speichern" /> &nbsp; ';
				echo '<a href="?page='.$page.'&amp;faq='.$arr['faq_id'].'">Abbrechen</a>';
				echo '</form>';

			}
			elseif (!LOGIN)
			{
					echo $requireLoginMsg;
			}				
			else
			{
				echo message("error","Du hast keine Berechtigung um diese Frage zu bearbeiten!");
			}		
		}		
		else
		{
			echo message('error','Diese Frage existiert nicht!');
		}
	}	
	
	
	/*************
	* Einsendung *
	*************/	
	elseif ($cat == "submit")
	{
		if ($_POST[encfname('faq_user_question_submit')]!="")
		{
			if ($_POST[encfname('faq_user_question')]!="" && $_POST[encfname('faq_description')] !="")
			{
				$res=dbquery("SELECT
					faq_id
				FROM
					faq
				WHERE 
					faq_question='".stripslashes($_POST[encfname('faq_user_question')])."'
				;");
				if (mysql_num_rows($res)==0)
				{
					$username = $_SESSION['etoahelp']['username']; //$_POST[encfname('faq_user_nick')]
					$email = $_SESSION['etoahelp']['email']; //$_POST[encfname('faq_user_email')]
					$userid = $_SESSION['etoahelp']['uid']; //$_POST[encfname('faq_user_email')]
				
					dbquery("INSERT INTO
						faq
					(
						faq_question,
						faq_description,
						faq_user_id,
						faq_user_nick,
						faq_user_email,
						faq_user_time,
						faq_updated,
						faq_user_ip,
						faq_user_host,
						faq_user_client
					) VALUES (
						'".addslashes($_POST[encfname('faq_user_question')])."',
						'".addslashes($_POST[encfname('faq_description')])."',
						".$userid.",
						'".addslashes($username)."',
						'".addslashes($email)."',
						".time().",
						".time().",
						'".$_SERVER['REMOTE_ADDR']."',
						'".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',
						'".$_SERVER['HTTP_USER_AGENT']."'
					);");
					$id = mysql_insert_id();
					
					$tags = explode(",",$_POST[encfname('tags')]);
					foreach ($tags as $t)
					{
						$t = trim($t);
						if ($t != "")
						{
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
							$sql="INSERT INTO help_tag_rel (domain,tag_id,item_id) VALUES ('faq',".$tagId.",".$id.");";
							dbquery($sql);
						}
					}
					
					
					$url = "http://etoa.ch/help/?page=faq&faq=".$id;
					echo message('success','Vielen Dank, deine Frage wurde gespeichert!');
					echo "<p><a href=\"$url\">Frage anschauen</a></p>";
					$text = $username." (".$email.") hat eine neue Frage geschrieben:\n\n".$_POST[encfname('faq_user_question')]."\n\n$url";
					mail($conf['faq_admin']['v'],"EtoA-FAQ: Neue Frage",$text);
					
				}
				else
				{
					echo message('error','Tut uns leid, diese Frage ist bereits vorhanden!');
				}
			}
			else
			{
				echo message('error','Du hast keinen Text eingegeben!');
			}
			genfkey();
		}
		genfkey();

		echo '<p>Stelle hier deine Frage, von der du denkst dass sie f&uuml;r die anderen Spieler auch interessant sein k&ouml;nnte.<br/>
Die Frage wird anschliessend durch einen Admin beantwortet und freigeschaltet.</p>';

		if (!LOGIN)
		{
			echo $requireLoginMsg;
		}
		else
		{

			echo '<form action="?page='.$page.'&amp;cat='.$cat.'" method="post">';
			/*echo '<p><label>Name:</label><br/>
			<input type="text" name="'.encfname('faq_user_nick').'" size="90" maxlength="200" /></p>
			<p><label>E-Mail (wird nicht ver&ouml;ffentlicht):</label><br/>
			<input type="text" name="'.encfname('faq_user_email').'" size="50" maxlength="50" /></p>';
			*/

			echo message("info",$rulesText);
			echo message("info",$submitInfoText);

			
			echo '<p><label>Titel:</label><br/>
			<input type="text" name="'.encfname('faq_user_question').'" size="100"/></p>
			<p><label>Beschreibung:</label><br/>
			<textarea name="'.encfname('faq_description').'" rows="8" cols="90"></textarea></p>';
			echo '<p><label>Tags (mit Komma trennen; z.B. Schiffe,Preis,Geschwindigkeit):</label><br/>
			<input type="text" name="'.encfname('tags').'" size="100"/></p>';

			$s = mt_rand(1,5);
			for ($x=0;$x<$s;$x++)
			{
				echo '<input type="submit" name="'.encfname('antispam'.$x).'" value="Einsenden" style="display:none;"  />';
			}
			
			
			
			echo '<input type="submit" name="'.encfname('faq_user_question_submit').'" value="Einsenden" />';
			$s = mt_rand(6,10);
			for ($x=5;$x<$s;$x++)
			{
				echo '<input type="submit" name="'.encfname('antispam'.$x).'" value="Einsenden" style="display:none;" />';
			}			
			echo '</form>';
		}
	}
	
	
	/***********
	* Overview *
	***********/
	else
	{
		$lim = isset($_GET['limit']) && $_GET['limit'] > 0 ? $_GET['limit'] : 0;
		$numlim = 25;
			
		$limit="$lim,$numlim";
		
		$res=dbquery("
		SELECT
			COUNT(faq_id)
		FROM 
			faq
		WHERE
			faq_deleted=0
		;
		");
		$arr=mysql_fetch_row($res);		
		$totalQuestions = $arr[0];
	
		if ($cat == "views")
		{
			$res=dbquery("
			SELECT
				*
			FROM 
				faq
			WHERE
				faq_deleted=0
			ORDER BY
				faq_views DESC
			LIMIT 
				$limit;
			");
		}			
		elseif ($cat == "rating")
		{
			$res=dbquery("
			SELECT
				*
			FROM 
				faq
			WHERE
				faq_deleted=0
			ORDER BY
				faq_vote DESC, faq_updated DESC
			LIMIT 
				$limit;
			");
			//,			faq_rating/faq_ratings AS rating
		}
		elseif ($cat == "my" && LOGIN)
		{
			$res=dbquery("
			SELECT
				*
			FROM 
				faq
			WHERE
				faq_deleted=0
				AND faq_user_id=".$_SESSION['etoahelp']['uid']."				
			ORDER BY
				faq_user_time DESC
			LIMIT 
				$limit;
			");		
		}
		elseif ($cat == "questions" && isset($_GET['userid']) && $_GET['userid'] > 0)
		{
			$res=dbquery("
			SELECT
				*
			FROM 
				faq
			WHERE
				faq_deleted=0
				AND faq_user_id=".$_GET['userid']."				
			ORDER BY
				faq_user_time DESC
			LIMIT 
				$limit;
			");		
		}	
		elseif ($cat == "answers" && isset($_GET['userid']) && $_GET['userid'] > 0)
		{
			$res=dbquery("
			SELECT
				f.*,
				c.comment_vote,
				c.comment_correct
			FROM 
				faq f
			INNER JOIN
				faq_comments c
				ON c.comment_faq_id=f.faq_id
				AND c.comment_user_id=".$_GET['userid']."				
				AND	f.faq_deleted=0
			GROUP BY 
				comment_id
			ORDER BY
				comment_time DESC
			LIMIT 
				$limit;
			");		
			$view = "short";
		}		
		elseif ($cat == "updated")
		{
			$res=dbquery("
			SELECT
				*
			FROM 
				faq
			WHERE
				faq_deleted=0
			ORDER BY
				faq_updated DESC
			LIMIT 
				$limit;
			");
		}
		elseif ($cat == "unanswered")
		{
			$res=dbquery("
			SELECT
				f.*
			FROM 
				faq f
			LEFT JOIN
				faq_comments c
				ON comment_faq_id = faq_id
				AND faq_deleted=0
			WHERE
				c.comment_id IS NULL
				AND f.faq_deleted=0
			GROUP BY
				f.faq_id
			ORDER BY
				faq_user_time DESC
			LIMIT 
				$limit;
			");
		}		
		else
		{
			$res=dbquery("
			SELECT
				*
			FROM 
				faq
			WHERE
				faq_deleted=0
			ORDER BY
				faq_user_time DESC
			LIMIT 
				$limit;
			");
		}
		
		if ($cat == "my" && !LOGIN)
		{
			echo $requireLoginMsg;
		}
		else
		{		
			$nr = mysql_num_rows($res);
			if ($nr>0)
			{
				$t = time();
				echo '<table class="faqtable">';
				while ($arr=mysql_fetch_array($res))
				{
					$text = text2html($arr['faq_question']);
					if (stristr($text,"<br"))
						$text = substr($text,0,strpos($text,"<br"));
						
					if (isset($view) && $view == "short")
					{
						echo "<tr>
						<td class=\"center\"><span class=\"stats ".($arr['comment_vote']>0 ? ' hasAnswers': '' )."\">".$arr['comment_vote']."</span></td>
						<td class=\"questionoutline\"><a href=\"?page=$page&amp;faq=".$arr['faq_id']."\" class=\"question\">".$text."</a><br/>
						</td>
						</tr>";
					}
					else
					{	
						$cres = dbquery("
						SELECT
							COUNT(comment_id)
						FROM
							faq_comments
						WHERE
							comment_faq_id=".$arr['faq_id']."
						");	
						$carr = mysql_fetch_row($cres);								
							
						if ($arr['faq_views'] >= 1000)
						{
							$views = floor($arr['faq_views']/1000);
							$viewsText = "kviews";
							$viewsaddedclass="manyViews";
						}
						else
						{
							$views = $arr['faq_views'];
							$viewsText = "views";
							$viewsaddedclass="notManyViews";
						}						
						if ($carr[0]>0)
							$numaddedclass="hasAnswers";
						else
							$numaddedclass="noAnswers";
							
						$ccres = dbquery("
						SELECT
							COUNT(comment_id)
						FROM
							faq_comments
						WHERE
							comment_faq_id=".$arr['faq_id']."
							AND comment_correct=1
						");						
						$ccarr = mysql_fetch_row($ccres);		
							
						if ($ccarr[0] == 1)
							$answerclass = "correctAnswer";
						else
							$answerclass = "";

						$timestr= $arr['faq_user_time'] > 0 ? tfs(time() - $arr['faq_user_time']) : "vor langer Zeit";
						$timestr.=  $arr['faq_user_nick'] != "" && $arr['faq_user_id']>0 ? " <a href=\"?page=user&id=".$arr['faq_user_id']."\">".stripslashes($arr['faq_user_nick'])."</a>"  : "";
						if ($arr['faq_updated'] > $arr['faq_user_time'])
							$timestr.= ", ".tfs(time() - $arr['faq_updated'])." aktualisiert";

						//round($arr['faq_rating']/$arr['faq_ratings'],1)
						echo "<tr>
						<td class=\"center\"><span class=\"stats\">".$arr['faq_vote']."</span><br/><span class=\"desc\">wertung</span></td>
						<td class=\"center\"><div class=\"answerbox $answerclass\"><span class=\"stats $numaddedclass\">".$carr[0]."</span><br/><span class=\"desc $numaddedclass\">".($carr[0] == 1 ? "antwort" : "antworten")."</span></td>
						<td class=\"center\"><span class=\"stats $viewsaddedclass\">".$views."</span></br><span class=\"desc $viewsaddedclass\">$viewsText</span></td>
						<td class=\"questionoutline\"><a href=\"?page=$page&amp;faq=".$arr['faq_id']."\" class=\"question\">".$text."</a><br/>
						<span class=\"date\">".$timestr;
						echo "</spam>
						</td>
						</tr>";
					}
				}		
				echo '</table>';
				
				echo "<p>Fragen: <b>".($lim+1)."</b>-<b>".($lim+$nr)."</b> von <b>$totalQuestions</b> &nbsp; ";
				if ($lim > 0)
					echo "<a href=\"?page=$page&amp;cat=$cat&amp;limit=".($lim-$numlim)."\">Vorherige anzeigen</a> ";
				if ($lim+$numlim < $totalQuestions)
					echo "<a href=\"?page=$page&amp;cat=$cat&amp;limit=".($lim+$numlim)."\">Weitere anzeigen</a> ";
				echo "</p>";

			}
			else
			{
				echo message("warning","Keine Fragen vorhanden!");
			}
		}
	}
?>
</div>