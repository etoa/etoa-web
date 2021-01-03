<h1>&Uuml;bersicht</h1>
Willkommen in der Loginserver-Administration!<br /><br />
Diese Seite dient der Verwaltung gewisser Bereiche der Login-Page.<br />
Besuche folgende Seiten für andere Administrationsmöglichkeiten:

<?php
$res = dbquery("
    SELECT
        t.threadID
    FROM
        " . wbbtable('thread') . " AS t
    WHERE
        t.boardID=" . get_config('status_board') . "
        AND t.isClosed=0
;");
$nr = mysql_num_rows($res);
if ($nr > 0) {
    echo "<h2 style=\"color:red\">Offene Statusmeldung</h2>";
    echo "Es gibt <a href=\"?page=status\">$nr offene Statusmeldung</a>!";
}

if (get_config('maintenance_mode', 0) == 1) {
    echo "<h2 style=\"color:#f00\">Wartungsmodus</h2>
		    <div>Der Wartungsmodus der Loginseite ist aktiv!</div>";
}
?>

<h2>Community-Administration</h2>
<ul>
    <li><a href="<?= forumUrl('admin') ?>">Forum-Administration</a></li>
</ul>

<h2>Game-Administration</h2>
<?php
$res = dbquery("
    SELECT *
    FROM " . dbtable('rounds') . "
    ORDER BY round_active
    DESC, round_name ASC
;");
if (mysql_num_rows($res) > 0) {
    echo "<ul>";
    while ($arr = mysql_fetch_array($res)) {
        echo "<li><a href=\"" . $arr['round_url'] . "/admin\">" . $arr['round_name'] . "</a></li>";
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
		" . wcftable('user_to_groups') . " t
	INNER JOIN
        " . wcftable('user') . " u
	ON t.userID=u.userID
	AND t.groupID = " . get_config('loginadmin_group') . ";
;");
echo "<ul>";
while ($arr = mysql_fetch_assoc($res)) {
    echo "<li>" . $arr['username'] . "</li>";
}
echo "</ul>";
?>
