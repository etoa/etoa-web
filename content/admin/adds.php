<h1>Werbung</h1>
<?php
if (isset($_POST['submit'])) {
    set_config('adds', $_POST['adds']);
    set_config('adds_news', $_POST['adds_news']);
    echo message("info", "Gespeichert!");
}

echo "<form action=\"?page=$page\" method=\"post\">";
echo "Reches Vertikalbanner:<br/>
    <textarea name=\"adds\" rows=\"30\" cols=\"120\">" . get_config('adds', '') . "</textarea><br/><br/>";
echo "News Horizontalbanner:<br/>
    <textarea name=\"adds_news\" rows=\"30\" cols=\"120\">" . get_config('adds_news', '') . "</textarea>";
echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"Übernehmen\" /> ";
echo "</form>";
