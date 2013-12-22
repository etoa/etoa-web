<h1>&Uuml;bersicht</h1>
Willkommen in der Loginserver-Administration!<br/><br/>
Diese Seite dient der Verwaltung gewisser Bereiche der Login-Page.<br/>
Besuche folgende Seiten für andere Administrationsmöglichkeiten:

<?PHP
	define('STATUS_BOARD_ID',$conf['status_board']['v']);
	define('POSTS_TABLE',"bb1_posts");
	define('THREADS_TABLE',"wbb1_1_thread");

	$res = dbquery("	
	SELECT 
		t.threadID
	FROM 
		".THREADS_TABLE." AS t
	WHERE
		t.boardID=".STATUS_BOARD_ID."
		AND t.isClosed=0
	");
	$nr=mysql_num_rows($res);
	if ($nr>0)
	{
		echo "<h2 style=\"color:red\">Offene Statusmeldung</h2>";
		echo "Es gibt <a href=\"?page=status\">$nr offene Statusmeldung</a>!";
	}	

	if ($conf['maintenance_mode']['v']==1)
	echo "<h2 style=\"color:#f00\">Wartungsmodus</h2>
		<div>Der Wartungsmodus der Loginseite ist aktiv!</div>";

?>

<h2>Community-Administration</h2>
<ul>
	<li><a href="<?PHP echo FORUM_URL;?>/acp">Forum-Administration</a></li>
</ul>

<h2>Game-Administration</h2>
<?PHP
	$res=dbquery("SELECT * FROM rounds ORDER BY round_active DESC, round_name ASC;");
	if (mysql_num_rows($res)>0)
	{
	echo "<ul>";
		while ($arr=mysql_fetch_array($res))
		{
			echo "<li><a href=\"".$arr['round_url']."/admin\">".$arr['round_name']."</a></li>";
		}		
		echo "</ul>";
	}
	
	echo "<h2>Zugriff</h2>
	<p>Folgende Leute haben Zugang auf diese Tool:</p>";
	$res = dbquery("
	SELECT
		u.userID,
		u.username
	FROM
		wcf1_user_to_groups t
	INNER JOIN
		wcf1_user u
	ON t.userID=u.userID
	AND t.groupID = ".$conf['loginadmin_group']['v'].";
	");
	echo "<ul>";
	while ($arr=mysql_fetch_assoc($res))
	{
		echo "<li>".$arr['username']."</li>";
	}
	echo "</ul>";
?>