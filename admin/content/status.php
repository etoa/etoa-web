<?PHP
	define('STATUS_BOARD_ID',$conf['status_board']['v']);
	define('POSTS_TABLE',"wbb1_1_post");
	define('THREADS_TABLE',"wbb1_1_thread");
?>

<h1>Statusmeldungen</h1>
<p>Hier können Status-Threads verwaltet werden, welche im Forenbereich 
<a href="<?PHP echo FORUM_URL;?>/board.php?boardid=<?PHP echo STATUS_BOARD_ID;?>">Statusmeldungen</a> zu finden sind.</p>
	
<?PHP

	$res = dbquery("	
	SELECT 
		t.threadID,
		t.topic,
		t.time,
		t.boardID,
		t.isClosed,
		t.lastPostTime
	FROM 
		".THREADS_TABLE." AS t
	WHERE
		t.boardID=".STATUS_BOARD_ID."
		AND t.isClosed=0
	ORDER BY 
		t.time DESC 
	");
	if (mysql_num_rows($res)>0)
	{
	echo "<h2>Aktive Meldungen</h2>";
	echo "<table class=\"tbl\">
	<tr>
		<th>Topic</th>
		<th>Letze Aktualisierung</th>
				<th>Antworten</th>
		<th>Status</th>
	</tr>";
	while ($arr=mysql_fetch_assoc($res))
	{
		echo "<tr>
		<td><a href=\"".FORUM_URL."/index.php?page=Thread&threadID=".$arr['threadID']."\">".$arr['topic']."</a></td>
		<td>".df($arr['lastPostTime'])."</td>
		<td>".$arr['replycount']."</td>
		<td>".($arr['isClosed']==1 ? "<span style=\"color:#0f0\">Abgeschlossen</span>" : "<span style=\"color:#f00\">Offen</span>")."</td>
		</tr>";
	}
	echo "</table>";
}
?>


<h2>Archivierte Meldungen</h2>
<?PHP
	$res = dbquery("	
	SELECT 
		t.threadID,
		t.topic,
		t.time,
		t.boardID,
		t.isClosed,
		t.lastPostTime,
		t.replies
	FROM 
		".THREADS_TABLE." AS t
	WHERE
		t.boardID=".STATUS_BOARD_ID."
		AND t.isClosed=1
	ORDER BY 
		t.time DESC 
	");
	echo "<table class=\"tbl\">
	<tr>
		<th>Topic</th>
		<th>Letze Aktualisierung</th>
		<th>Antworten</th>
		<th>Status</th>
	</tr>";
	while ($arr=mysql_fetch_assoc($res))
	{
		echo "<tr>
		<td><a href=\"".FORUM_URL."/index.php?page=Thread&threadID=".$arr['threadID']."\">".$arr['topic']."</a></td>
		<td>".df($arr['lastPostTime'])."</td>
		<td>".$arr['replies']."</td>
		<td>".($arr['isClosed']==1 ? "<span style=\"color:#0f0\">Abgeschlossen</span>" : "<span style=\"color:#f00\">Offen</span>")."</td>
		</tr>";
	}
	echo "</table>";

?>