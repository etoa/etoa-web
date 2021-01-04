<?PHP

use App\Support\ForumBridge;
use App\Support\StringUtil;

$site_title = 'Artikel';

$rulesText = message("info", "<b>Regeln:</b> Keine Namen von Mitspielern im Text, keine Koordinaten, kein Spam, kein Fluchwörter, keine Werbung.
    Es gelten dieselben <a href=\"" . ForumBridge::url('board', get_config('rules_board')) . "\">Regeln</a> wie im Forum (z.B. betreffend illegaler Inhalte) sowie die allgemeine Nettiquette.
    Missbrauch dieser Funktion kann zu einer Sperre im Forum und/oder im Spiel selbst führen.
    Mit dem Absenden erklärst du dich einverstanden, dass deine Foren-Accountdaten (Username, E-Mail, Benutzer-ID) mit der Frage gespeichert werden.
    Um Missbrauchsfälle aufzudecken wird auch deine IP-Adresse sowie der verwendete Browser aufgezeichnet.");

$requireLoginMsg = message("warning", "Du bist nicht eingeloggt! Bitte logge dich <a href=\"login\">hier</a> mit deinem Forum-Account ein!");

if ((isset($_GET['article']) && $_GET['article'] != "") || (isset($_GET['a']) && $_GET['a'] != "")) {
    if (isset($_GET['a']) && $_GET['a'] != "") {
        if (substr($_GET['a'], 0, 5) == "wiki/") {
            $_GET['a'] = substr($_GET['a'], 5);
        }
        $res = dbquery($sql = "
            SELECT hash
            FROM " . dbtable('articles') . "
            WHERE alias='" . mysql_real_escape_string($_GET['a']) . "'
            ORDER BY rev DESC
            LIMIT 1
            ;");
        if (mysql_num_rows($res) > 0) {
            $arr = mysql_fetch_assoc($res);
            $hash = $arr['hash'];
        }
    } else {
        $hash = $_GET['article'];
    }

    if (isset($_GET['rev']) && $_GET['rev'] > 0) {
        $sql = "
            SELECT *
            FROM
                " . dbtable('articles') . "
            WHERE
                hash='" . mysql_real_escape_string($hash) . "'
                AND rev=" . $_GET['rev'] . "
            ;";
    } else {
        $sql = "
            SELECT *
            FROM " . dbtable('articles') . "
			WHERE hash='" . mysql_real_escape_string($hash) . "'
			ORDER BY rev DESC
			LIMIT 1
            ;";
    }
    $res = dbquery($sql);
    if (mysql_num_rows($res) > 0) {
        $arr = mysql_fetch_assoc($res);

        echo popText();

        if (isset($_GET['source']) && $_GET['source'] == 1) {
            echo "<h1>" . $arr['title'] . ": Quellcode</h1>";
            echo "<pre>" . $arr['text'] . "</pre>";
        } else {
            echo Michelf\MarkdownExtra::defaultTransform($arr['text']);
        }

        $author = $arr['user_nick'] != "" && $arr['user_id'] > 0 ? "<a href=\"?page=user&id=" . $arr['user_id'] . "\">" . $arr['user_nick'] . "</a>"  : "Unbekannt";
        echo "<br/><br/>";

        $tres = dbquery("
            SELECT t.id, t.name
            FROM " . dbtable('help_tag') . " t
            INNER JOIN " . dbtable('help_tag_rel') . " r
                ON r.tag_id=t.id
                AND r.domain='wiki'
                AND r.item_id=" . intval($arr['id']) . "
            ;");
        if (mysql_num_rows($tres) > 0) {
            echo "<div class=\"questiontags\"><b>Tags:</b>  ";
            $tags = array();
            while ($tarr = mysql_fetch_assoc($tres)) {
                echo "<a href=\"?page=tags&amp;id=" . $tarr['id'] . "\">" . $tarr['name'] . "</a>  ";
                $tags[$tarr['id']] = $tarr['name'];
            }
            echo "</div>";
        }
        echo "<p>Dieser Artikel wurde zuletzt bearbeitet von " . $author . " " . StringUtil::diffFromNow($arr['changed']) . ", <a href=\"?page=$page&amp;diff=" . $arr['hash'] . "&amp;range=" . ($arr['rev'] - 1) . ":" . $arr['rev'] . "\">Revision " . $arr['rev'] . "</a></p>	";
    } else {
        echo message("error", "Dieser Artikel existiert nicht!");
    }

    echo "<input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"Zur Übersicht\" /> &nbsp;";
    if (isset($_GET['source']) && $_GET['source'] == 1)
        echo "<input type=\"button\" onclick=\"document.location='?page=$page&article=" . $arr['hash'] . (isset($_GET['rev']) ? "&rev=" . $_GET['rev'] : '') . "'\" value=\"Normale Ansicht\" /> &nbsp; ";
    else
        echo "<input type=\"button\" onclick=\"document.location='?page=$page&article=" . $arr['hash'] . (isset($_GET['rev']) ? "&rev=" . $_GET['rev'] : '') . "&source=1'\" value=\"Quelltext\" /> &nbsp; ";
    echo "<input type=\"button\" onclick=\"document.location='?page=$page&revs=" . $arr['hash'] . "'\" value=\"" . (isset($_GET['rev']) && $_GET['rev'] > 0 ? 'Andere Versionen' : 'Vorherige Versionen') . "\" /> &nbsp; ";
    echo "<input type=\"button\" onclick=\"document.location='?page=$page&edit=" . $arr['hash'] . "'\" value=\"Bearbeiten\" />";
} elseif (isset($_GET['diff']) && isset($_GET['range'])) {
    $hash = $_GET['diff'];
    $res = dbquery("
        SELECT *
        FROM " . dbtable('articles') . "
        WHERE hash='" . mysql_real_escape_string($hash) . "'
        ORDER BY rev DESC
        LIMIT 1
        ;");
    if (mysql_num_rows($res) > 0) {
        $arr = mysql_fetch_assoc($res);
        list($v1, $v2) = explode(":", $_GET['range']);

        echo "<h1>" . $arr['title'] . ": Differenz</h1>";
        echo "<h2>Version $v1 zu Version $v2</h2>";

        $author = $arr['user_nick'] != "" && $arr['user_id'] > 0 ? "<a href=\"?page=user&id=" . $arr['user_id'] . "\">" . $arr['user_nick'] . "</a>"  : "Unbekannt";
        echo "<p>Autor: " . $author . ",  " . StringUtil::diffFromNow($arr['changed']) . "</p>	";

        $v1res = dbquery("
            SELECT *
			FROM " . dbtable('articles') . "
			WHERE
				hash='" . mysql_real_escape_string($hash) . "'
				AND rev=" . intval($v1) . "
            ;");
        $v1arr = mysql_fetch_assoc($v1res);
        $v2res = dbquery("
            SELECT *
			FROM " . dbtable('articles') . "
			WHERE
				hash='" . mysql_real_escape_string($hash) . "'
				AND rev=" . intval($v2) . "
            ;");
        $v2arr = mysql_fetch_assoc($v2res);

        if ($v1arr['text'] == $v2arr['text']) {
            echo message("info", "Keine Änderungen");
        } else {
            $header_content = "<style type=\"text/css\">\n" . file_get_contents(__DIR__ . '../../../vendor/qazd/text-diff/css/style.css') . "</style>\n";
            echo Qazd\TextDiff::render($v1arr['text'], $v2arr['text']);
            echo "<br>";
        }
    } else {
        echo message("error", "Dieser Artikel existiert nicht!");
    }

    echo "<input type=\"button\" onclick=\"document.location='?page=$page&article=" . $hash . "'\" value=\"Zum Artikel\" /> &nbsp; ";
    echo "<input type=\"button\" onclick=\"document.location='?page=$page&revs=" . $hash . "'\" value=\"Andere Versionen\" /> &nbsp; ";
    echo "<input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"Zur Übersicht\" /> &nbsp;";
} elseif (isset($_GET['revs']) && $_GET['revs'] != '') {
    $hash = $_GET['revs'];
    echo "<h1>Revisionen</h1>";
    $res = dbquery("
		SELECT *
		FROM " . dbtable('articles') . "
		WHERE hash='" . mysql_real_escape_string($hash) . "'
		ORDER BY rev DESC
		LIMIT 1
		;");
    if (mysql_num_rows($res) > 0) {
        $arr = mysql_fetch_assoc($res);
        echo "<h2>" . $arr['title'] . "</h2>";
        $res = dbquery("
            SELECT *
            FROM " . dbtable('articles') . "
			WHERE hash='" . mysql_real_escape_string($hash) . "'
			ORDER BY rev DESC
			;");
        $nr = mysql_num_rows($res);
        if ($nr > 0) {
            echo "<table><tr><th>Rev</th><th>Titel</th><th>Änderung</th><th>Autor</th><th>Diff</th></tr>";
            $i = 0;
            while ($arr = mysql_fetch_assoc($res)) {
                if ($i == 0)
                    $cv = $arr['rev'];
                $author = $arr['user_nick'] != "" && $arr['user_id'] > 0 ? "<a href=\"?page=user&id=" . $arr['user_id'] . "\">" . $arr['user_nick'] . "</a>"  : "Unbekannt";
                echo "<tr><td><b>" . $arr['rev'] . "</b></td>
					<td><a href=\"?page=$page&amp;article=" . $arr['hash'] . "&amp;rev=" . $arr['rev'] . "\">" . $arr['title'] . "</a></td>
					<td>" . StringUtil::diffFromNow($arr['changed']) . "</td>
					<td>$author</td>
					<td>";
                if ($i < $nr - 1)
                    echo "<a href=\"?page=$page&amp;diff=" . $arr['hash'] . "&amp;range=" . $arr['rev'] . ":" . ($arr['rev'] - 1) . "\">mit vorheriger Version</a> &nbsp; ";
                if ($i > 0)
                    echo "<a href=\"?page=$page&amp;diff=" . $arr['hash'] . "&amp;range=" . $arr['rev'] . ":" . ($arr['rev'] + 1) . "\">mit nächster Version</a> &nbsp; ";
                if ($i > 0)
                    echo "<a href=\"?page=$page&amp;diff=" . $arr['hash'] . "&amp;range=" . $arr['rev'] . ":" . $cv . "\">mit aktueller Version</a> &nbsp;";
                echo "</td>
					</tr>";
                $i++;
            }
            echo "</table>";
        }
        echo "<p><input type=\"button\" onclick=\"document.location='?page=$page&article=" . $hash . "'\" value=\"Zurück\" /></p>";
    } else {
        echo message("error", "Dieser Artikel existiert nicht!");
    }
} elseif (isset($_GET['edit']) && $_GET['edit'] != '') {
    if ($auth) {
        $hash = $_GET['edit'];

        echo "<h2>Artikel bearbeiten</h2>";

        $res = dbquery("
			SELECT *
			FROM " . dbtable('articles') . "
			WHERE hash='" . mysql_real_escape_string($hash) . "'
			ORDER BY rev DESC
			LIMIT 1
			;");
        if (mysql_num_rows($res) > 0) {
            $arr = mysql_fetch_assoc($res);
            $title = $arr['title'];
            $text = $arr['text'];

            if (isset($_POST['submit'])) {
                $title = $_POST['title'];
                $text = $_POST['text'];
                if (isset($_POST['title']) && trim($_POST['title']) != "") {
                    $hres = dbquery("
                        SELECT MAX(rev)
                        FROM " . dbtable('articles') . "
                        WHERE hash='" . $arr['hash'] . "'
                        ;");
                    $harr = mysql_Fetch_row($hres);
                    if (dbquery("
                        INSERT INTO " . dbtable('articles') . "
						(
							hash,
							alias,
							title,
							text,
							user_id,
							user_nick,
							user_ip,
							created,
							changed,
							rev
						)
						VALUES
						(
							'" . $arr['hash'] . "',
							'" . StringUtil::prettyUrlString($_POST['title']) . "',
							'" . mysql_real_escape_string($_POST['title']) . "',
							'" . mysql_real_escape_string($_POST['text']) . "',
							" . $_SESSION['etoahelp']['uid'] . ",
							'" . mysql_real_escape_string($_SESSION['etoahelp']['username']) . "',
							'" . $_SERVER['REMOTE_ADDR'] . "',
							UNIX_TIMESTAMP(),
							UNIX_TIMESTAMP(),
							" . $harr[0] . "+1
						);")) {
                        $id = mysql_insert_id();
                        $tags = explode(",", $_POST[encfname('tags')]);
                        foreach ($tags as $t) {
                            $t = trim($t);
                            if ($t != "") {
                                $res = dbquery("
                                    SELECT id
                                    FROM " . dbtable('help_tag') . "
                                    WHERE name='" . mysql_real_escape_string($t) . "'
                                    ;");
                                if ($idarr = mysql_fetch_row($res)) {
                                    $tagId = $idarr[0];
                                } else {
                                    dbquery("
                                        INSERT INTO " . dbtable('help_tag') . "
                                        (name)
                                        VALUES ('" . mysql_real_escape_string($t) . "')
                                        ;");
                                    $tagId = mysql_insert_id();
                                }
                                dbquery("
                                    INSERT INTO " . dbtable('help_tag_rel') . "
                                    (domain,tag_id,item_id)
                                    VALUES ('wiki'," . $tagId . "," . $id . ")
                                    ;");
                            }
                        }
                        pushText(message('success', 'Artikel wurde gespeichert!'));
                        forwardInternal("?page=$page&article=" . $arr['hash']);
                    }
                } else {
                    echo message("error", "Du hast keinen Titel eingegeben!");
                }
            }

            echo $rulesText;
            echo "<form action=\"?page=$page&amp;edit=" . $arr['hash'] . "\" method=\"post\">";
            echo "<p><label>Titel</label><br/><input type=\"text\" size=\"40\" name=\"title\" value=\"$title\" /></p>";
            echo "<p><label>Text</label><br/><textarea name=\"text\" rows=\"28\" cols=\"100\">$text</textarea><br/>
				<a href=\"http://www.darkcoding.net/software/markdown-quick-reference/\" target=\"_blank\">Schnellreferenz</a> &nbsp;
				<a href=\"http://daringfireball.net/projects/markdown/syntax\" target=\"_blank\">Syntax</a> &nbsp;
				<a href=\"http://michelf.com/projects/php-markdown/extra/\" target=\"_blank\">Erweiterte Syntax</a>
				</p>";

            $tres = dbquery("
                SELECT t.id, t.name
                FROM " . dbtable('help_tag') . " t
                INNER JOIN " . dbtable('help_tag_rel') . "  r
                    ON r.tag_id=t.id
                    AND r.domain='wiki'
                    AND r.item_id=" . intval($arr['id']) . "
                ;");
            $tags = array();
            while ($tarr = mysql_fetch_assoc($tres)) {
                $tags[$tarr['id']] = $tarr['name'];
            }
            $tagstr = implode(",", $tags);
            echo '<p><label>Tags (mit Komma trennen; z.B. Schiffe,Preis,Geschwindigkeit):</label><br/>
				<input type="text" name="' . encfname('tags') . '" size="100" value="' . $tagstr . '"/></p>';
            echo "<input type=\"submit\" name=\"submit\" value=\"Speichern\" /> &nbsp;
				<input type=\"button\" onclick=\"document.location='?page=$page&amp;article=" . $arr['hash'] . "'\" value=\"Abbrechen\" />";
            echo "</form>";

            $author = $arr['user_nick'] != "" && $arr['user_id'] > 0 ? "<a href=\"?page=user&id=" . $arr['user_id'] . "\">" . $arr['user_nick'] . "</a>"  : "Unbekannt";
            echo "<p>Dieser Artikel wurde zuletzt bearbeitet von " . $author . " " . StringUtil::diffFromNow($arr['changed']) . ", Revision " . $arr['rev'] . "</p>";
        } else {
            echo message("error", "Artikel exisitert nicht!");
        }
    } else {
        echo $requireLoginMsg;
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    if ($auth) {
        echo "<h2>Neuer Artikel</h2>";

        $title = $text = '';

        if (isset($_POST['submit'])) {
            $title = $_POST['title'];
            $text = $_POST['text'];
            if (isset($_POST['title']) && trim($_POST['title']) != "") {
                $hash = sha1($_POST['title'] . time() . $_SESSION['etoahelp']['uid']);
                if (dbquery("
                    INSERT INTO " . dbtable('articles') . "
					(
						hash,
						alias,
						title,
						text,
						user_id,
						user_nick,
						user_ip,
						created,
						changed
					)
					VALUES
					(
						'" . $hash . "',
						'" . StringUtil::prettyUrlString($_POST['title']) . "',
						'" . mysql_real_escape_string($_POST['title']) . "',
						'" . mysql_real_escape_string($_POST['text']) . "',
						" . $_SESSION['etoahelp']['uid'] . ",
						'" . mysql_real_escape_string($_SESSION['etoahelp']['username']) . "',
						'" . $_SERVER['REMOTE_ADDR'] . "',
						UNIX_TIMESTAMP(),
						UNIX_TIMESTAMP()
					);")) {
                    $id = mysql_insert_id();

                    $tags = explode(",", $_POST[encfname('tags')]);
                    foreach ($tags as $t) {
                        $t = trim($t);
                        if ($t != "") {
                            $res = dbquery("
                                SELECT id
                                FROM " . dbtable('help_tag') . "
                                WHERE name='" . mysql_real_escape_string($t) . "'
                                ;");
                            if ($idarr = mysql_fetch_row($res)) {
                                $tagId = $idarr[0];
                            } else {
                                dbquery("
                                    INSERT INTO " . dbtable('help_tag') . "
                                    (name)
                                    VALUES ('" . mysql_real_escape_string($t) . "')
                                    ;");
                                $tagId = mysql_insert_id();
                            }
                            dbquery("
                                INSERT INTO " . dbtable('help_tag_rel') . "
                                (domain,tag_id,item_id)
                                VALUES ('wiki'," . $tagId . "," . $id . ")
                                ;");
                        }
                    }

                    pushText(message('success', 'Artikel wurde gespeichert!'));
                    forwardInternal("?page=$page&article=" . $hash);
                }
            } else {
                echo message("error", "Du hast keinen Titel eingegeben!");
            }
        }

        echo $rulesText;
        echo "<form action=\"?page=$page&amp;action=create\" method=\"post\">";
        echo "<p><label>Titel</label><br/><input type=\"text\" size=\"40\" name=\"title\" value=\"$title\" /></p>";
        echo "<p><label>Text</label><br/><textarea name=\"text\" rows=\"28\" cols=\"100\">$text</textarea><br/>
			<a href=\"http://www.darkcoding.net/software/markdown-quick-reference/\" target=\"_blank\">Schnellreferenz</a> &nbsp;
			<a href=\"http://daringfireball.net/projects/markdown/syntax\" target=\"_blank\">Syntax</a> &nbsp;
			<a href=\"http://michelf.com/projects/php-markdown/extra/\" target=\"_blank\">Erweiterte Syntax</a>
			</p>";
        echo '<p><label>Tags (mit Komma trennen; z.B. Schiffe,Preis,Geschwindigkeit):</label><br/>
			<input type="text" name="' . encfname('tags') . '" size="100"/></p>';

        echo "<input type=\"submit\" name=\"submit\" value=\"Speichern\" /> &nbsp; <input type=\"button\" onclick=\"document.location='?page=$page'\" value=\"Abbrechen\" />";
        echo "</form>";
    } else {
        echo $requireLoginMsg;
    }
} else {
    echo "<h1>Artikel</h1>";
    $res = dbquery("
		SELECT * FROM (
            SELECT
                title,
                alias,
                changed,
                id,
                hash,
                rev
            FROM " . dbtable('articles') . " a
            ORDER BY hash ASC,rev DESC
        ) AS a
		GROUP BY hash
		ORDER BY title
		;");
    echo "<h2>Übersicht</h2>";
    if (mysql_num_rows($res) > 0) {
        echo "<ul>";
        while ($arr = mysql_fetch_assoc($res)) {
            $url = $arr['alias'] != '' ? "?page=$page&amp;a=" . $arr['alias'] : "?page=$page&amp;article=" . $arr['hash'];

            echo "<li><a href=\"$url\">" . $arr['title'] . "</a>
				<span style=\"font-size:8pt;\">(bearbeitet " . StringUtil::diffFromNow($arr['changed']) . ", rev " . $arr['rev'] . ")</span></li>";
        }
        echo "</ul>";
    } else {
        echo "Es existieren noch keine Artikel!";
    }

    echo "<br/><br/>";
    if ($auth) {
        echo "<input type=\"button\" onclick=\"document.location='?page=$page&amp;action=create'\" value=\"Neuer Artikel\" /> &nbsp;";
    } else {
        echo message("warning", "Du musst eingeloggt sein um einen neuen Artikel erstelen zu können! Bitte logge dich <a href=\"login\">hier</a> mit deinem Forum-Account ein!");
    }
}
