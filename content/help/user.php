<?PHP

use App\Models\Article;
use App\Models\Faq;
use App\Models\FaqComment;
use App\Support\ForumBridge;
use App\Support\StringUtil;

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $site_title = 'Benutzerprofil';
    echo "<h1>Benutzerprofil</h1>";
    $user = ForumBridge::userById($_GET['id']);
    if ($user !== null) {
        echo "<h2>Profil von " . $user['username'] . "</h2>
        <table>
            <tr>
                <td><img src=\"" . $user['avatar'] . "\" alt=\"Avatar\" class=\"faquseravatar\" /></td>
                <td>
                    <b>" . $user['username'] . "</b><br/>
                    ".($user['title'] != '' ? $user['title'] . "<br/>" : '')."<br/>
                    <b>Dabei seit:</b> " . StringUtil::diffFromNow($user['registration_date']) . "<br/>
                    <a href=\"" . ForumBridge::url('user', $user['id']) . "\">Profil im EtoA Forum anzeigen</a>
                </td>
            </tr>
        </table>";

        $questionsAsked = Faq::countByUser($user['id']);

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_comments') . " c
			INNER JOIN
                " . dbtable('faq') . " f
			ON c.comment_faq_id=f.faq_id
			AND f.faq_user_id=" . $user['id'] . "
			AND c.comment_correct=1;");
        $darr = mysql_fetch_row($dres);
        $answersMarkedCorrect = $darr[0];

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_vote') . " v
			INNER JOIN
                " . dbtable('faq') . " f
			ON f.faq_id=v.faq_id
			AND f.faq_user_id=" . $user['id'] . "
			AND v.value=1;");
        $darr = mysql_fetch_row($dres);
        $questionsVotedPositive = $darr[0];

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_vote') . " v
			INNER JOIN
                " . dbtable('faq') . " f
			ON f.faq_id=v.faq_id
			AND f.faq_user_id=" . $user['id'] . "
			AND v.value=-1;");
        $darr = mysql_fetch_row($dres);
        $questionsVotedNegative = $darr[0];

        $ccres = dbquery("
			SELECT
				COUNT(comment_id),
				SUM(comment_correct)
			FROM
                " . dbtable('faq_comments') . "
			WHERE
				comment_user_id=" . $user['id'] . ";");
        $ccarr = mysql_fetch_row($ccres);
        $answersWritten = $ccarr[0];
        $acceptedAnswers = $ccarr[1];

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_comment_vote') . " v
			INNER JOIN
                " . dbtable('faq_comments') . " f
			ON f.comment_id=v.comment_id
			AND f.comment_user_id=" . $user['id'] . "
			AND v.value=1;");
        $darr = mysql_fetch_row($dres);
        $answersVotedPositive = $darr[0];

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_comment_vote') . " v
			INNER JOIN
                " . dbtable('faq_comments') . " f
			ON f.comment_id=v.comment_id
			AND f.comment_user_id=" . $user['id'] . "
			AND v.value=-1;");
        $darr = mysql_fetch_row($dres);
        $answersVotedNegative = $darr[0];

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_vote') . "
			WHERE
				user_id=" . $user['id'] . "
				AND value=1;");
        $darr = mysql_fetch_row($dres);
        $questionPostitiveVotes = $darr[0];

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_vote') . "
			WHERE
				user_id=" . $user['id'] . "
				AND value=-1;");
        $darr = mysql_fetch_row($dres);
        $questionNegativeVotes = $darr[0];

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_comment_vote') . "
			WHERE
				user_id=" . $user['id'] . "
				AND value=1;");
        $darr = mysql_fetch_row($dres);
        $answerPositiveVotes = $darr[0];

        $dres = dbquery("
			SELECT
				COUNT(*)
			FROM
                " . dbtable('faq_comment_vote') . "
			WHERE
				user_id=" . $user['id'] . "
				AND value=-1;");
        $darr = mysql_fetch_row($dres);
        $answerNegativeVotes = $darr[0];


        echo "<h3>Fragen</h3>";
        echo "
			<table><tr><td class=\"faquserprofilebignum\"><a href=\"?page=faq&amp;cat=questions&amp;userid=" . $user['id'] . "\">" . $questionsAsked . "</a></td><td>";
        echo "Als positiv bewertete Fragen: <b>" . $questionsVotedPositive . "</b><br/>";
        echo "Als negativ bewertete Fragen: <b>" . $questionsVotedNegative . "</b><br/>";
        echo "Antworten als korrekt akzeptiert: <b>" . $answersMarkedCorrect . "</b>
			</td></tr></table>";

        echo "<h3>Antworten</h3>
			<table><tr><td class=\"faquserprofilebignum\"><a href=\"?page=faq&amp;cat=answers&amp;userid=" . $user['id'] . "\">" . $answersWritten . "</a></td><td>";
        echo "Als positiv bewertete Antworten: <b>" . $answersVotedPositive . "</b><br/>";
        echo "Als negativ bewertete Antworten: <b>" . $answersVotedNegative . "</b><br/>";
        echo "Antworten wurden vom Fragesteller als korrekt akzeptiert: <b>" . $acceptedAnswers . "</b>
			</td></tr></table>";

        echo "<h3>Bewertung von Fragen und Antworten</h3>";
        echo "Fragen positiv bewertet: <b>" . $questionPostitiveVotes . "</b><br/>
			Fragen negativ bewertet: <b>" . $questionNegativeVotes . "</b><br/>";
        echo "Antwort positiv bewertet: <b>" . $answerPositiveVotes . "</b><br/>
			Antwort negativ bewertet: <b>" . $answerNegativeVotes . "</b>";

        echo "<h3>Wiki</h3>";
        $wres = dbquery("
            SELECT
				COUNT(id)
			FROM
                " . dbtable('articles') . "
			WHERE
				user_id='" . $user['id'] . "'
				AND rev=1
			;");
        $warr = mysql_fetch_row($wres);
        $wikiCreated = $warr[0];
        $wres = dbquery("
            SELECT
				COUNT(id)
			FROM
                " . dbtable('articles') . "
			WHERE
				user_id='" . $user['id'] . "'
				AND rev>1
			;");
        $warr = mysql_fetch_row($wres);
        $wikiEdited = $warr[0];
        echo "Artikel begonnen: <b>" . $wikiCreated . "</b><br/>";
        echo "Bearbeitungen: <b>" . $wikiEdited . "</b><br/>";

        echo "<h3>Tags</h3>";
        $tres = dbquery("
            SELECT t.id,t.name,COUNT(t.id) as cnt
			FROM " . dbtable('help_tag') . " t
			INNER JOIN
                " . dbtable('help_tag_rel') . " r ON t.id=r.tag_id
			INNER JOIN
                " . dbtable('faq') . " f ON r.item_id=f.faq_id
			LEFT JOIN " . dbtable('faq_comments') . " a ON a.comment_faq_id=f.faq_id
			WHERE
			(comment_user_id=" . $user['id'] . "
			OR faq_user_id=" . $user['id'] . ")
			GROUP BY t.id
			ORDER BY cnt DESC,t.name
			;");
        while ($tarr = mysql_fetch_assoc($tres)) {
            echo "<a href=\"?page=tags&amp;id=" . $tarr['id'] . "&amp;userid=" . $user['id'] . "\">" . $tarr['name'] . "</a> " . $tarr['cnt'] . "x<br/>";
        }
    } else {
        echo message("error", "Benutzer nicht gefunden!");
    }
} else {
    $site_title = 'Aktive Benutzer';

    echo "<h1>Aktive Benutzer</h1>";

    echo "<h2>Fragen & Antworten</h2>";

    echo "<table class=\"faquserlist\">
        <tr>
            <th></th><th>User</th>
            <th>Fragen</th>
            <th>Antworten</th>
            <th>Wiki-Aktivität</th>
        </tr>";
    foreach (ForumBridge::activeUsers() as $user) {
        $questionsAsked = Faq::countByUser($user['id']);
        $answersWritten = FaqComment::countByUser($user['id']);
        $wiki = Article::countByUser($user['id']);
        echo "<tr>
                <td class=\"avatar\"><img src=\"" . $user['avatar'] . "\" alt=\"Avatar\" /></td>
                <td><a href=\"?page=$page&amp;id=" . $user['id'] . "\">" . $user['username'] . "</a></td>
                <td class=\"num\">" . ($questionsAsked > 0 ? "<a href=\"?page=faq&amp;cat=questions&amp;userid=" . $user['id'] . "\">$questionsAsked</a>" : $questionsAsked) . "</td>
                <td class=\"num\">" . ($answersWritten > 0 ? "<a href=\"?page=faq&amp;cat=answers&amp;userid=" . $user['id'] . "\">$answersWritten</a>" : $answersWritten) . "</td>
                <td class=\"num\">" . $wiki . "</td>
			</tr> ";
    }
    echo "</table>";
}
