<h1>Java-Script Code</h1>
<?php
if (isset($_POST['submit'])) {
    set_config('indexjscript', $_POST['indexjscript']);
    set_config('footer_js', $_POST['footer_js']);
    echo message("info", "Gespeichert!");
}

echo "<form action=\"?page=$page\" method=\"post\">";
echo "Header<br/>";
echo "<textarea name=\"indexjscript\" rows=\"30\" cols=\"120\">" . get_config('indexjscript', '') . "</textarea><br/>";
echo "Footer<br/>";
echo "<textarea name=\"footer_js\" rows=\"30\" cols=\"120\">" . get_config('footer_js', '') . "</textarea><br/>";
echo "<p><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /></p>";
echo "</form>";
