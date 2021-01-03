<br />
<?PHP
$thread_id = get_config('rules_thread');
$res = dbquery("
    SELECT * FROM " . wbbtable('post') . "
    WHERE threadid=" . $thread_id . "
    ORDER BY time ASC LIMIT 1;");
echo "<div class=\"boxLine\"></div>";
if (mysql_num_rows($res) > 0) {
    $arr = mysql_fetch_array($res);
    echo "<div class=\"boxTitle\"><h2>" . $arr['subject'] . "</h2>";
    echo "</div>";
    echo "<div class=\"boxLine\"></div>";
    echo "<div class=\"boxData\">";
    echo text2html($arr["message"]);
} else {
    echo "<div class=\"boxTitle\">Es trat ein Fehler auf!</div>";
    echo "<div class=\"boxLine\"></div>";
    echo "<div class=\"boxData\">";
    echo "<i>Regeln nicht vorhanden!</i>";
}

echo "</div>";
echo "<div class=\"boxLine\"></div>";
?>
