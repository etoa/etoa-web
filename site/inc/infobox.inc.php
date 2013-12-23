<?PHP
	define('LATEST_POSTS_NUM', 5);

	$res=dbquery("
	SELECT
		COUNT(sessionID)
	FROM
		wcf1_session
	WHERE
		lastActivityTime >".(time()-1000)."
	;");
	$arr2 = mysql_fetch_row($res);	


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
	LIMIT ".LATEST_POSTS_NUM.";");	
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
