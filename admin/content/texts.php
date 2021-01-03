<h1>Texte</h1>
<?PHP
if (isset($_POST['submit']) && $_POST['text_text'] != "" && $_POST['text_id'] > 0) {
    dbquery("
        UPDATE ".dbtable('texts')."
		SET
            text_text='" . addslashes($_POST['text_text']) . "',
            text_last_changes=" . time() . "
        WHERE text_id=" . $_POST['text_id'] . "
    ;");
    $_GET['id'] = $_POST['text_id'];
    echo message("info", "Änderungen gespeichert!");
}

$res = dbquery("
    SELECT *
    FROM ".dbtable('texts')."
;");
echo "<form action=\"?page=$page\" method=\"post\">";
if (mysql_num_rows($res) > 0) {
    echo "<table class=\"tbl\">";
    echo "<tr><th>Text:</th><td><select name=\"text_select\" id=\"text_select\" onchange=\"document.location='?page=$page&id='+this.options[this.selectedIndex].value\">";
    $first = null;
    while ($arr = mysql_fetch_array($res)) {
        if ($first == null)
            $first = $arr;
        echo "<option value=\"" . $arr['text_id'] . "\"";
        if (isset($_GET['id']) && $_GET['id'] == $arr['text_id']) {
            echo " selected=\"selected\"";
            $first = $arr;
        }
        echo ">" . $arr['text_name'] . " (" . $arr['text_keyword'] . ")</option>";
    }
    echo "</select> <input type=\"button\" value=\"Anzeigen\" onclick=\"document.location='?page=$page&id='+document.getElementById('text_select').options[document.getElementById('text_select').selectedIndex].value\" /></td></tr>";
    echo "<tr><th>Name, Schlüsselwort:</th><td>" . $first['text_name'] . ", " . $first['text_keyword'] . "</td></tr>";
    echo "<tr><th>Letzte Änderung:</th><td>" . df($first['text_last_changes']) . "</td></tr>";
    echo "<tr><td colspan=\"2\"><textarea name=\"text_text\" rows=\"28\" cols=\"100\">" . stripslashes($first['text_text']) . "</textarea></td></tr>";
    echo "</table><br/><input type=\"hidden\" name=\"text_id\" value=\"" . $first['text_id'] . "\" /><input type=\"submit\" name=\"submit\" value=\"&Uuml;bernehmen\" /> ";
} else {
    echo "<i>Keine Texte vorhanden!</i><br/><br/>";
}
echo "</form>";
