<br />
<?PHP

use App\Support\ForumBridge;
use App\Support\StringUtil;

$thread_id = get_config('rules_thread', 0);
$thread = ForumBridge::thread($thread_id);
echo "<div class=\"boxLine\"></div>";
if ($thread !== null) {
    echo "<div class=\"boxTitle\"><h2>" . $thread['subject'] . "</h2>";
    echo "</div>";
    echo "<div class=\"boxLine\"></div>";
    echo "<div class=\"boxData\">";
    echo StringUtil::text2html($thread["message"]);
} else {
    echo "<div class=\"boxTitle\">Es trat ein Fehler auf!</div>";
    echo "<div class=\"boxLine\"></div>";
    echo "<div class=\"boxData\">";
    echo "<i>Regeln nicht vorhanden!</i>";
}

echo "</div>";
echo "<div class=\"boxLine\"></div>";
?>
