<h1>Einstellungen</h1>
<?php
$keys = [
    'news_board',
    'rules_board',
    'rules_thread',
    'ts_link',
    'faq_admin',
    'loginadmin_group',
    'infobox_board_blacklist',
    'status_board',
    'support_board',
    'forum_mail',
    'forum_url',
];

if (isset($_POST['submit'])) {
    foreach ($keys as $key) {
        set_config($key, $_POST['config_value'][$key]);
    }
    echo message("info", "Änderungen gespeichert!");
}

echo "<form action=\"?page=$page\" method=\"post\">";
echo "<table class=\"tbl\">";
echo "<tr><th>Name:</th><th>Wert:</th></tr>";
foreach ($keys as $key) {
    echo "<tr><td>" . $key . "</td>";
    echo "<td><textarea name=\"config_value[" . $key . "]\" rows=\"5\" cols=\"50\">" . get_config($key, null, false) . "</textarea></td>";
}
echo "</table><br/><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";
