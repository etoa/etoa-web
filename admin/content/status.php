<?PHP
	define('STATUS_BOARD_ID',$conf['status_board']['v']);
	define('POSTS_TABLE',"wbb1_1_post");
	define('THREADS_TABLE',"wbb1_1_thread");

?>

<h1>Statusmeldungen</h1>
<p>Hier können Status-Threads verwaltet werden, welche im Forenbereich 
<a href="http://www.etoa.ch/forum/board.php?boardid=<?PHP echo STATUS_BOARD_ID;?>">Statusmeldungen</a> zu finden sind.</p>
	
<?PHP
/*
echo "<h2>Neue Meldung</h2>";
if (isset($_POST['submit']))
{
	dbquery("
	INSERT INTO
		".THREADS_TABLE."
	(
		boardid,
		topic,
		starttime,
		starterid,
		starter,
		lastposttime,
		lastposterid,
		lastposter,
		visible	
	)
	VALUES
	(
		".STATUS_BOARD_ID.",
		'".addslashes($_POST['topic'])."',
		".time().",
		".$_SESSION['etoadmin']['uid'].",
		'".$_SESSION['etoadmin']['nick']."',
		".time().",
		".$_SESSION['etoadmin']['uid'].",
		'".$_SESSION['etoadmin']['nick']."',
		1
	)
	");
	$tid = mysql_insert_id();
	dbquery("
	INSERT INTO
		".POSTS_TABLE."
	(	
		threadid,
		userid,
		username,
		posttopic,
		posttime,
		message,
		visible
	)
	VALUES
	(
		".$tid.",
		".$_SESSION['etoadmin']['uid'].",
		'".$_SESSION['etoadmin']['nick']."',
		'".addslashes($_POST['topic'])."',		
		".time().",
		'".addslashes($_POST['message'])."',
		1
	);
	");
	dbquery("
	UPDATE
		bb1_boards
	SET
  	lastthreadid=".$tid.",
		lastposttime=".time().",
		lastposterid=".$_SESSION['etoadmin']['uid'].",
		lastposter='".$_SESSION['etoadmin']['nick']."',
		threadcount=threadcount+1,
		postcount=postcount+1
	WHERE
		boardid=".STATUS_BOARD_ID."
	");
	echo "<p>Beitrag gespeichert!</p>";
}

?>

<form action="?page=status" method="post">
<table class="tbl">
	<tr><th>Titel</th><td><input type="text" name="topic" value="" size="70" maxlength="255" /></td></tr>
	<tr><th>Nachricht</th><td><textarea name="message" rows="7" cols="60"></textarea></td></tr>
</table><br/>
<input type="submit" name="submit" value="Erstellen"/></form>

<?PHP
	/*
	if (isset($_POST['asubmit']) || isset($_POST['aclose']))
	{
		dbquery("
		INSERT INTO
			".POSTS_TABLE."
		(	
			threadid,
			userid,
			username,
			posttime,
			message,
			visible
		)
		VALUES
		(
			".$_POST['atid'].",
			".$_SESSION['etoadmin']['uid'].",
			'".$_SESSION['etoadmin']['nick']."',
			".time().",
			'".addslashes($_POST['amessage'])."',
			1
		);
		");
		dbquery("
		UPDATE
			bb1_boards
		SET
	  	lastthreadid=".$_POST['atid'].",
			lastposttime=".time().",
			lastposterid=".$_SESSION['etoadmin']['uid'].",
			lastposter='".$_SESSION['etoadmin']['nick']."',
			postcount=postcount+1
		WHERE
			boardid=".STATUS_BOARD_ID."
		");		
		dbquery("
		UPDATE
			".THREADS_TABLE."
		SET
			lastposttime=".time().",
			lastposterid=".$_SESSION['etoadmin']['uid'].",
			lastposter='".$_SESSION['etoadmin']['nick']."',
			closed=".(isset($_POST['aclose'])?1:0).",
			replycount=replycount+1
		WHERE
			threadid=".$_POST['atid']."
		");		
	}*/


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
		<td><a href=\"http://www.etoa.ch/forum/index.php?page=Thread&threadID=".$arr['threadID']."\">".$arr['topic']."</a></td>
		<td>".df($arr['lastPostTime'])."</td>
		<td>".$arr['replycount']."</td>
		<td>".($arr['isClosed']==1 ? "<span style=\"color:#0f0\">Abgeschlossen</span>" : "<span style=\"color:#f00\">Offen</span>")."</td>
		</tr>";
		/*
		<td>
			<form action=\"?page=status\" method=\"post\">
			<textarea name=\"amessage\" rows=\"3\" cols=\"40\"></textarea>
			<input type=\"hidden\" name=\"atid\" value=\"".$arr['threadid']."\"/>
			<br/><input type=\"submit\" name=\"asubmit\" value=\"Antworten\"/>
			<input type=\"submit\" name=\"aclose\" value=\"Antworten &amp; Schliessen\"/>
			<input type=\"submit\" name=\"close\" value=\"Schliessen\"/>
			</form>		
		</td>
		
		*/
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
		<td><a href=\"http://www.etoa.ch/forum/index.php?page=Thread&threadID=".$arr['threadID']."\">".$arr['topic']."</a></td>
		<td>".df($arr['lastPostTime'])."</td>
		<td>".$arr['replies']."</td>
		<td>".($arr['isClosed']==1 ? "<span style=\"color:#0f0\">Abgeschlossen</span>" : "<span style=\"color:#f00\">Offen</span>")."</td>
		</tr>";
	}
	echo "</table>";

?>