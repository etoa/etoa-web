<h1>Servermeldung</h1>
<?php
if (isset($_POST['submit'])) {
    set_config('server_notice', $_POST['server_notice']);
    set_config('server_notice_updated', time());
    set_config('server_notice_color', $_POST['server_notice_color']);
    echo message("info", "Gespeichert!");
}

echo "<form action=\"?page=$page\" method=\"post\">";
echo "<textarea name=\"server_notice\" rows=\"10\" cols=\"120\">" . get_config('server_notice', '') . "</textarea><br/>";
echo "Farbe: <input size=\"12\" name=\"server_notice_color\" value=\"" . get_config('server_notice_color', 'orange')  . "\" /><br/>";
echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"Übernehmen\" /> ";
echo "</form>";
