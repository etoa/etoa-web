<h1>Buttons</h1>
<?php
if (isset($_POST['submit'])) {
    set_config('buttons', $_POST['config_value']);
    echo message("info", "Gespeichert!");
}
echo "<form action=\"?page=$page\" method=\"post\">";
echo "<textarea name=\"config_value\" rows=\"30\" cols=\"120\">" . get_config('buttons', '') . "</textarea>";
echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"Übernehmen\" /> ";
echo "</form>";
