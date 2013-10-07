<?PHP

	define('POSTS_TABLE',"wbb1_1_post");
	define('THREADS_TABLE',"wbb1_1_thread");
	define('NEWS_BOARD_ID',$conf['news_board']['v']);
	define('STATUS_BOARD_ID',$conf['status_board']['v']);

	echo '<h1>Herzlich willkommen zu Escape to Andromeda!</h1>';
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxTitle\"><h2>Über das Spiel</h2></div>";
	echo "<div class=\"boxLine\"></div>";
	echo "<div class=\"boxData\">";
	show_text("home");
	echo "</div>";
	echo "<div class=\"boxLine\"></div>";	
	
	echo "<br/>";

	if ($conf['adds_news']['v']!="")
	{
		echo "<div style=\"text-align:center;\">";
		$adds = explode("<br/>",$conf['adds_news']['v']);
		echo stripslashes($adds[array_rand($adds)]);
		echo "</div>";
	echo "<div style=\"font-size:8pt;color:#0f0;text-align:center;font-weight:bold\">Unterstütze EtoA indem du die Angebote
	unserer Werbepartner beachtest</div>";
		
	}
	

	echo "<h1>News</h1>";
	$res=dbquery("
	SELECT 
		t.topic,
    t.time,
		t.threadID,
		t.isClosed,
		t.boardID,
		t.lastPostTime,
    pp.message,
    pp.username,
    pp.userid,
    pp.threadid,
    pp.lastEditTime    
	FROM 
		".THREADS_TABLE." t
	INNER JOIN (
    SELECT 
      p.message,
      p.username,
      p.userid,
      p.threadid,
      p.lastEditTime
    FROM
      ".POSTS_TABLE." p
    ORDER BY p.time  
    ) pp
    ON
      pp.threadID=t.threadID 
		AND (
      t.boardID=".NEWS_BOARD_ID." 
		OR 
		(	t.boardID=".STATUS_BOARD_ID."
			AND t.isClosed=0
		)
		)
	GROUP BY 
		t.threadID 
	ORDER BY 
		t.time DESC 
	LIMIT 
		5
	;");
	if (mysql_num_rows($res)>0)
	{
		while ($arr = mysql_fetch_array($res))
		{
			$pres = dbquery("
			SELECT 
				COUNT(threadID)
			FROM 
				".POSTS_TABLE."
			WHERE
				threadID=".$arr['threadID']."
			;");
			$parr = mysql_fetch_row($pres);
			
			$url = "http://www.etoa.ch/forum/index.php?page=Thread&amp;threadID=";
			$replyUrl = "http://www.etoa.ch/forum/index.php?form=PostAdd&amp;threadID=";

			if ($parr[0]-1 > 1)
				$cmts = "<a style=\"color:#fb0;\" href=\"$url".$arr['threadID']."\">".($parr[0]-1)." Kommentare vorhanden</a> | ";
			elseif ($parr[0]-1 > 0)
				$cmts = "<a style=\"color:#fb0;\" href=\"$url".$arr['threadID']."\">1 Kommentar vorhanden</a> | ";
			else
				$cmts = "";
			
			echo "<div class=\"boxLine\"></div>";
			echo "<div class=\"boxTitle\"><img src=\"site/images/logo_mini.gif\" alt=\"Logo Mini\" style=\"width:53px;height:30px;float:left;margin-right:10px;\" />
			".($arr['boardID']==STATUS_BOARD_ID ? "SERVERSTATUS " : "")."<a href=\"$url".$arr['threadID']."\">".$arr['topic']."</a>";
			
			if ($arr['boardID']==STATUS_BOARD_ID && $arr['closed']==1)
			{
				echo " &nbsp; <span style=\"color:#0f0;\">Abgeschlossen (".df($arr['lastposttime']).")</span>";
			}			
			
			
			echo "
			<br/><span class=\"subtitle\">".df($arr['time'])." von  
			<a href=\"http://www.etoa.ch/forum/profile.php?userid=".$arr['userid']."\">".$arr['username']."</a> ";
			if ($arr['lastEditTime']>0)
				echo " (Letzte &Auml;nderung: ".df($arr['lastEditTime']).")";
			echo "</span></div>";
			echo "<div class=\"boxLine\"></div>";
			echo "<div class=\"boxData\">".text2html($arr["message"])."";
			echo "<div style=\"color:#fb0;font-size:9pt;margin-top:10px;\">".$cmts."
			<a style=\"color:#fb0;\" href=\"$replyUrl".$arr['threadID']."\">Kommentiere diese Nachricht</a></div>
			</div>";
			echo "<div class=\"boxLine\"></div><br/><br/>";
			
		}
		echo "Alle älteren News findest du <a href=\"forum/board.php?boardid=".NEWS_BOARD_ID."\">hier</a><br/>";
	}
	else
	{
		echo "<p class=\"loginmsg\"><i>Keine News vorhanden!</i></p>";
	}



?>
