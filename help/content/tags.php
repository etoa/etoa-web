<h1>Tags</h1>

<?PHP

use App\Models\Article;
use App\Models\Faq;
use App\Models\Tag;
use App\Support\ForumBridge;
use App\Support\TagCloud;

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $tag = Tag::find($_GET['id']);
    if ($tag !== null) {

        echo "<h2>FAQ</h2>";
        $user = null;
        if (isset($_GET['userid']) && $_GET['userid'] > 0) {
            $faqs = Faq::withTagIdAndUserId(intval($_GET['id']), $_GET['userid']);
            $user = ForumBridge::userById($_GET['userid']);
        } else {
            $faqs = Faq::withTagId(intval($_GET['id']));
        }
        if (count($faqs) > 0) {
            echo '<b>' .count($faqs) . '</b> Einträge getaggt mit <b>' . $tag->name . '</b>';
            if ($user !== null) {
                echo ' mit Beiträgen von <a href="?page=user&amp;id=' . $user['id'] . '">' . $user['username'] . '</a>';
            }
            echo ':<br/><br/>';
            echo "<ul>";
            foreach ($faqs as $faq) {
                echo "<li><a href=\"?page=faq&amp;faq=" . $faq->id . "\">" . $faq->question . "</a></li>";
            }
            echo '</ul>';
        } else {
            echo "<i>Deine Suche nach <b>" . $tag->name . "</b> ergab keine Treffer!</i><br/><br/>";
        }

        echo "<h2>Wiki</h2>";
        $articles = Article::withTagId(intval($_GET['id']));
        if (count($articles) > 0) {
            echo '<b>' . count($articles) . '</b> Einträge getaggt mit <b>' . $tag->name . '</b>';
            echo ':<br/><br/>';
            echo "<ul>";
            foreach ($articles as $article) {
                echo "<li><a href=\"?page=article&amp;article=" . $article->hash . "\">" . $article->title . "</a></li>";
            }
            echo '</ul><br/>';
        } else {
            echo "<i>Deine Suche nach <b>" . $tag->name . "</b> ergab keine Treffer!</i><br/><br/>";
        }
    } else {
        echo "<i>Dieser Tag existiert nicht!</i><br/><br/>";
    }
} else {
    $tags = (new TagCloud())->generate(13, 28);
    foreach ($tags as $tag) {
        echo "<a style=\"font-size: " . $tag['size'] . "px\" href=\"?page=tags&amp;id=" . $tag['id'] . "\" title=\"" . $tag['count'] . " Einträge getaggt mit '" . $tag['name'] . "'\">" . $tag['name'] . "</a> ";
    }
}
