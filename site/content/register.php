<?PHP
echo "<br/><div class=\"boxLine\"></div>";
echo "<div class=\"boxTitle\"><h2>Melde dich f&uuml;r eine Runde an</h2></div>";
echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxData\">";
echo "Bitte w&auml;hle eine Runde aus:<ul>";
foreach ($rounds as $k => $v) {
    echo "<li><a href=\"" . $v['url'] . "/show.php?index=register\">" . $v['name'] . "</a>";
    if ($v['startdate'] > 0)
        echo " (online seit " . date("d.m.Y", $v['startdate']) . ")";
    echo "</li>";
}
echo "</ul>";
echo "</div>";
echo "<div class=\"boxLine\"></div>";
