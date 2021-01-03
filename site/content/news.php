<?PHP
$news_board_id = get_config('news_board');
$status_board_id = get_config('status_board');
$num_news = 3;

echo '<h1>Herzlich willkommen zu Escape to Andromeda!</h1>';
echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxTitle\"><h2>Über das Spiel</h2></div>";
echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxData\">";
show_text("home");
echo "</div>";
echo "<div class=\"boxLine\"></div>";

echo "<br/>";

if (get_config('adds_news') != "") {
    echo "<div style=\"text-align:center;\">";
    $adds = explode("<br/>", get_config('adds_news'));
    echo stripslashes($adds[array_rand($adds)]);
    echo "</div>";
    echo "<div style=\"font-size:8pt;color:#0f0;text-align:center;font-weight:bold\">Unterstütze EtoA indem du die Angebote
	unserer Werbepartner beachtest</div>";
}

echo "<h1>News</h1>";
$res = dbquery("
	SELECT
		t.topic,
    t.time,
		t.threadID,
		t.isClosed,
		t.boardID,
		t.lastPostTime,
    pp.message,
    pp.username,
    pp.userid,
    pp.threadid,
    pp.lastEditTime
	FROM
		" . wbbtable('thread') . " t
	INNER JOIN (
    SELECT
      p.message,
      p.username,
      p.userid,
      p.threadid,
      p.lastEditTime
    FROM
      " . wbbtable('post') . " p
    ORDER BY p.time
    ) pp
    ON
      pp.threadID=t.threadID
		AND (
      t.boardID=" . $news_board_id . "
		OR
		(	t.boardID=" . $status_board_id . "
			AND t.isClosed=0
		)
		)
	GROUP BY
		t.threadID
	ORDER BY
		t.time DESC
	LIMIT
		" . $num_news . "
	;");
if (mysql_num_rows($res) > 0) {
    while ($arr = mysql_fetch_array($res)) {
        $pres = dbquery("
			SELECT
				COUNT(threadID)
			FROM
				" . wbbtable('post') . "
			WHERE
				threadID=" . $arr['threadID'] . "
			;");
        $parr = mysql_fetch_row($pres);

        if ($parr[0] - 1 > 1)
            $cmts = "<a style=\"color:#fb0;\" href=\"" . forumUrl('thread', $arr['threadID']) . "\">" . ($parr[0] - 1) . " Kommentare vorhanden</a> | ";
        elseif ($parr[0] - 1 > 0)
            $cmts = "<a style=\"color:#fb0;\" href=\"" . forumUrl('thread', $arr['threadID']) . "\">1 Kommentar vorhanden</a> | ";
        else
            $cmts = "";

        echo "<div class=\"boxLine\"></div>";
        echo "<div class=\"boxTitle\"><img src=\"site/images/logo_mini.gif\" alt=\"Logo Mini\" style=\"width:53px;height:30px;float:left;margin-right:10px;\" />
			" . ($arr['boardID'] == $status_board_id ? "SERVERSTATUS " : "") . "<a href=\"" . forumUrl('thread', $arr['threadID']) . "\">" . $arr['topic'] . "</a>";

        if ($arr['boardID'] == $status_board_id && $arr['closed'] == 1) {
            echo " &nbsp; <span style=\"color:#0f0;\">Abgeschlossen (" . df($arr['lastposttime']) . ")</span>";
        }

        echo "
			<br/><span class=\"subtitle\">" . df($arr['time']) . " von
			<a href=\"" . forumUrl('user', $arr['userid']) . "\">" . $arr['username'] . "</a> ";
        if ($arr['lastEditTime'] > 0) {
            echo " (Letzte &Auml;nderung: " . df($arr['lastEditTime']) . ")";
        }
        echo "</span></div>";
        echo "<div class=\"boxLine\"></div>";
        echo "<div class=\"boxData\">" . text2html($arr["message"]) . "";
        echo "<div style=\"color:#fb0;font-size:9pt;margin-top:10px;\">" . $cmts . "
			<a style=\"color:#fb0;\" href=\"" . forumUrl('addpost', $arr['threadID']) . "\">Kommentiere diese Nachricht</a></div>
			</div>";
        echo "<div class=\"boxLine\"></div><br/><br/>";
    }
    echo "Alle älteren News findest du <a href=\"" . forumUrl('board', $news_board_id) . "\">hier</a><br/><br/>";
} else {
    echo "<p class=\"loginmsg\"><i>Keine News vorhanden!</i></p>";
}
