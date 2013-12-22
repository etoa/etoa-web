<?PHP
	/*
	$res=dbquery("
	SELECT
		COUNT(userid)
	FROM
		bb1_users
	;");
$arr1 = mysql_fetch_row($res);*/
	$res=dbquery("
	SELECT
		COUNT(sessionID)
	FROM
		wcf1_session
	WHERE
		lastActivityTime >".(time()-1000)."
	;");
	$arr2 = mysql_fetch_row($res);	

	/*
	$res=dbquery("
	SELECT
		COUNT(postID)
	FROM
		wbb1_1_post
		;");
	$arr3 = mysql_fetch_row($res);	
	$res=dbquery("
	SELECT
		COUNT(threadID)
	FROM
		wbb1_1_thread
	;");
	$arr4 = mysql_fetch_row($res);	
	 */

	echo "<h2>Neues aus dem Forum</h2>
	<span style=\"color:#0f0;font-size:9pt;\">".$arr2[0]." Leute online</span>";
	$bl = explode(",",$conf['infobox_board_blacklist']['v']);
	$bls = "";
	foreach ($bl as $bli)
	{
		$bls .=" AND t.boardid!=".$bli." ";
	}
	$res=dbquery("
	SELECT
		t.topic,
		p.postID,
		t.threadID,
		p.username,
		p.time
	FROM
		wbb1_1_thread t
	INNER JOIN
		wbb1_1_post p
		ON p.postID = (
        SELECT p2.`postID`
        FROM `wbb1_1_post` p2
        WHERE p2.`threadID` = t.`threadID`
        ".$bls."
        ORDER BY p2.`time` DESC
        LIMIT 1
    )		
  ORDER BY 
  	p.time DESC
	LIMIT 7;");	
	echo "<div id=\"forum\" style=\"\"><ul id=\"forumthreadlist\">";
	while ($arr = mysql_fetch_assoc($res))
	{
		echo "<li><a href=\"".FORUM_URL."/index.php?page=Thread&amp;postID=".$arr['postID']."#post".$arr['postID']."\">".$arr['topic']."</a> <span style=\"color:#aaa;font-size:80%\">".tfs(time()-$arr['time'])."</span></li>";
	}
	echo "</ul></div>";
	
	$res=dbquery("
	SELECT
		COUNT(faq_id)
	FROM
		faq
	WHERE
		faq_deleted=0
	;");
	$arr = mysql_fetch_row($res);
	$res=dbquery("
	SELECT
		COUNT(*)
	FROM
		articles
	;");
	$arr1 = mysql_fetch_row($res);	
	echo "<br/><h2>Hilfecenter</h2>
	<span style=\"color:#0f0;font-size:9pt;\">".($arr[0]+$arr1[0])." Einträge</span><br/>";
	$res=dbquery("
	SELECT
		faq_question,
		faq_id,
		faq_user_time
	FROM 
		faq
	WHERE
		faq_deleted=0
	ORDER BY faq_updated DESC
	LIMIT 
	5;");
	echo "<ul id=\"helplist\">";
	while($arr = mysql_fetch_assoc($res))
	{
		$txt = text2html($arr['faq_question']);
		if (strlen($txt)>30)
			$txt = substr($txt,0,24)."...";
		echo "<li><a href=\"help/?page=faq&amp;faq=".$arr['faq_id']."\">".$txt."</a></li>";
	}
	echo "</ul>";

	$res = dbquery("SELECT * FROM (SELECT t.id,t.name,COUNT(r.item_id) AS cnt
	FROM help_tag t
	INNER JOIN help_tag_rel r ON t.id=r.tag_id
	INNER JOIN faq f ON r.item_id=f.faq_id
	GROUP BY t.id
	ORDER BY cnt DESC LIMIT 10) AS t
	ORDER BY t.name
	;");
	echo "<b>Tags:</b><br/>";
	while ($arr = mysql_fetch_assoc($res))
	{	
		echo "<a href=\"help/?page=tags&amp;id=".$arr['id']."\">".$arr['name']."</a> ";
	}
	
/*
	$res = dbquery("SELECT * FROM rounds WHERE round_active=1");
	if (mysql_num_rows($res)>0)
	{
		while ($arr=mysql_fetch_array($res))
		{
			echo "<h2>".$arr['round_name']." &nbsp;";
			if ($arr['round_status_online']==1)
			{
				$s = new ServerInfo($arr['round_alturl']);
				echo "<span style=\"color:#0f0;font-size:9pt;\">online (".$s->usersOnline().")</span>";
			}
			else
			{
				echo "<span style=\"color:#f90;font-size:9pt;\">offline</span>";
			}
			echo "&nbsp; <span style=\"font-size:9pt;\">[<a href=\"javascript:;\" onclick=\"toggleBox('r".$arr['round_id']."details')\">Details</a>]</span>";
			echo "</h2>";
			echo "<div id=\"r".$arr['round_id']."details\" style=\"display:none;\">";

			if ($arr['round_status_online']==1)
			{
				echo "Online: ".$s->usersOnline()."<br/>";
				echo "Registriert: ".$s->usersRegistered()."<br/>";
				echo "Bevölkert: ".$s->planetPopulation()."<br/>";
				echo "Aktualisiert: ".df($arr['round_status_checked'])."<br/>";
				if ($arr['round_status_changed']>0)
				{
					echo "Online seit: ".df($arr['round_status_changed'])."<br/>";
				}
				echo "Ping: ".$arr['round_status_ping']." ms<br/>";
				echo "Rangliste: <a href=\"".$arr['round_url']."/show.php?index=stats\">Anzeigen</a><br/>";
			}
			else
			{
				echo "<span style=\"color:#f90\">Server offline</span><br/>";
				echo "Aktualisiert: ".df($arr['round_status_checked'])."<br/>";
				echo "Offlinse seit: ".df($arr['round_status_changed'])."<br/>";
				echo "Ping: ".$arr['round_status_ping']." ms<br/>";
			}
			echo "<br/></div>";
		}	
	}


	$res=dbquery("
	SELECT
		COUNT(userid)
	FROM
		bb1_users
	;");
	$arr1 = mysql_fetch_row($res);
	$res=dbquery("
	SELECT
		COUNT(userid)
	FROM
		bb1_sessions
	WHERE
		lastactivity>".(time()-1000)."
	;");
	$arr2 = mysql_fetch_row($res);	
	$res=dbquery("
	SELECT
		COUNT(postid)
	FROM
		bb1_posts
	;");
	$arr3 = mysql_fetch_row($res);	
	$res=dbquery("
	SELECT
		COUNT(threadid)
	FROM
		bb1_threads
	;");
	$arr4 = mysql_fetch_row($res);	
	echo "<h2>Forum";
	echo "&nbsp; <span style=\"color:#0f0;font-size:9pt;\">online (".$arr2[0].")</span> &nbsp;
	<span style=\"font-size:9pt;\">[<a href=\"javascript:;\" onclick=\"toggleBox('forum')\">Details</a>]</span>";
	echo "</h2><div id=\"forum\" style=\"display:none;\">";
	$res=dbquery("
	SELECT
		t.topic,
		p.posttime,
		p.username,
		p.postid
	FROM
		bb1_posts as p
	INNER JOIN
		bb1_threads as t
		ON p.threadid=t.threadid
	ORDER BY
		p.posttime DESC
	LIMIT 1;");
	$arr = mysql_fetch_row($res);
	echo "Neuster Post: <br/>
	<a href=\"forum/thread.php?postid=".$arr[3]."#post".$arr[3]."\">".$arr[0]."</a> 
	<br/><span style=\"font-size:8pt\"><b>(".$arr[2].", ".df($arr[1]).")</b></span>";	
	echo "<br/>User: ".$arr2[0]." / ".$arr1[0]."<br/>";
	echo "Themen: ".nf($arr4[0])." &nbsp; ";
	echo "Posts: ".nf($arr3[0])."<br/>
	<br/></div>";

	
	$s = new TeamspeakInfo('http://213.133.123.35/ts');
	echo "<h2>TeamSpeak &nbsp;";
	if ($s->online)
	{
		echo "<span style=\"color:#0f0;font-size:9pt;\">online (".$s->usersOnline().")</span> &nbsp;";
		echo "<span style=\"font-size:9pt;\">[<a href=\"javascript:;\" onclick=\"window.open('http://ts.etoa.net','ts','width=700,height=600,status=no,scrollbars=yes')\">Infos</a>]</span>";
	}
	else
	{
		echo "<span style=\"color:#f90;font-size:9pt;\">offline</span>";
	}
	echo "</h2>";


			echo "<h2>CS-Server &nbsp;";
			echo "<span style=\"color:#0f0;font-size:9pt;\">online</span>";
			echo "&nbsp; <span style=\"font-size:9pt;\">[<a href=\"http://cs.etoa.net\">Statistiken</a>]</span>";
			echo "</h2>";


	$res=dbquery("
	SELECT
		COUNT(faq_id)
	FROM
		faq
	WHERE
		faq_cat_id>0
	;");
	$arr = mysql_fetch_row($res);
	echo "<br/><h2>Hilfecenter (".$arr[0]." Themen)</h2>";
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
		1;");
	$arr = mysql_fetch_array($res);
	echo "Neuste Frage [<a href=\"help/?page=faq&amp;cat=-3\" style=\"text-decoration:none;\">Gib mir mehr</a>]:<br/>
	<a href=\"help/?page=faq&amp;faq=".$arr['faq_id']."\">".text2html($arr['faq_question'])."</a><br/>";
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
	WHERE 
		faq_id!=".$arr['faq_id']."
	ORDER BY RAND()
	LIMIT 
		1;");
	$arr = mysql_fetch_array($res);
	echo "Zufällige Frage: <br/><a href=\"help/?page=faq&amp;faq=".$arr['faq_id']."\">".text2html($arr['faq_question'])."</a>";


*/
	
	if ($conf['server_notice']['v']!="")
	{
		if ($conf['server_notice']['p2'])
			$color = $conf['server_notice']['p2'];
		else
			$color = "#fff";
		echo "<br/><div style=\"border:1px solid ".$color.";padding:4px;background:#223;color:".$color."\">";
		echo text2html($conf['server_notice']['v']);
		echo "<br/><div style=\"margin-top:5px;font-size:8pt;\">Aktualisiert: ".df($conf['server_notice']['p1'])."</div>";
		echo "</div><br/>";
	}
		
	
?>
