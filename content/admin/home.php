<h1>Übersicht</h1>
Willkommen in der Loginserver-Administration!<br /><br />
Diese Seite dient der Verwaltung gewisser Bereiche der Login-Page.<br />
Besuche folgende Seiten für andere Administrationsmöglichkeiten:

<?php

use App\Support\ForumBridge;

if (isMaintenanceModeActive()) {
    echo "<h2 style=\"color:#f00\">Wartungsmodus</h2>
		    <div>Der Wartungsmodus der Loginseite ist aktiv!</div>";
}
?>

<h2>Community-Administration</h2>
<ul>
    <li><a href="<?= ForumBridge::url('admin') ?>">Forum-Administration</a></li>
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
$admins = ForumBridge::usersOfGroup(get_config('loginadmin_group'));
echo "<ul>";
foreach ($admins as $admin) {
    echo '<li><a href="' . ForumBridge::url('user', $admin['id']) . '">' . $admin['username'] . '</a></li>';
}
echo "</ul>";
?>
