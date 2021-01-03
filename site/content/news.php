<?PHP

use App\Support\ForumBridge;
use App\Support\StringUtil;
use App\Support\TextUtil;

$news_board_id = get_config('news_board');
$status_board_id = get_config('status_board');
$num_news = 3;

echo '<h1>Herzlich willkommen zu Escape to Andromeda!</h1>';
echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxTitle\"><h2>Über das Spiel</h2></div>";
echo "<div class=\"boxLine\"></div>";
echo "<div class=\"boxData\">";
echo TextUtil::get("home");
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

if (!$newsContent = apcu_fetch('page-news')) {
    ob_start();
    echo "<h1>News</h1>";
    $threads = ForumBridge::newsPosts($num_news, $news_board_id, $status_board_id);
    if (count($threads) > 0) {
        foreach ($threads as $thread) {
            echo "<div class=\"boxLine\"></div>";
            echo "<div class=\"boxTitle\">
                <img src=\"site/images/logo_mini.gif\" alt=\"Logo Mini\" style=\"width:53px;height:30px;float:left;margin-right:10px;\" />
                " . ($thread['board_id'] == $status_board_id ? "SERVERSTATUS " : "") . "<a href=\"" . ForumBridge::url('thread', $thread['id']) . "\">" . $thread['topic'] . "</a>";
            if ($thread['board_id'] == $status_board_id && $arr['closed'] == 1) {
                echo " &nbsp; <span style=\"color:#0f0;\">Abgeschlossen (" . StringUtil::dateFormat($arr['lastposttime']) . ")</span>";
            }
            echo "<br/><span class=\"subtitle\">" . StringUtil::dateFormat($thread['time']) . " von
                <a href=\"" . ForumBridge::url('user', $thread['user_id']) . "\">" . $thread['user_name'] . "</a> ";
            if ($thread['updated_at'] > 0) {
                echo " (Letzte Änderung: " . StringUtil::dateFormat($thread['updated_at']) . ")";
            }
            echo "</span></div>";
            echo "<div class=\"boxLine\"></div>";
            echo "<div class=\"boxData\">" . StringUtil::text2html($thread["message"]) . "";
            echo "<div style=\"color:#fb0;font-size:9pt;margin-top:10px;\">";
            $replies = $thread['post_count'] - 1;
            if ($replies > 0) {
                echo "<a style=\"color:#fb0;\" href=\"" . ForumBridge::url('thread', $thread['id']) . "\">" . $replies . " Kommentare vorhanden</a> | ";
            }
            echo "<a style=\"color:#fb0;\" href=\"" . ForumBridge::url('addpost', $thread['id']) . "\">Kommentiere diese Nachricht</a></div></div>";
            echo "<div class=\"boxLine\"></div><br/><br/>";
        }
        echo "Alle älteren News findest du <a href=\"" . ForumBridge::url('board', $news_board_id) . "\">hier</a><br/><br/>";
    } else {
        echo "<p class=\"loginmsg\"><i>Keine News vorhanden!</i></p>";
    }

    $newsContent = ob_get_clean();
    apcu_add('page-news', $newsContent, config('caching.apcu_timeout'));
}
echo $newsContent;
