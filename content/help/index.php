<?php
    $site_title = 'Willkommen';
?>

<h1>Willkommen im EtoA-Hilfe-Center</h1>

<table class="indextable">
    <tr>
        <td>
            <h3>FAQ</h3>
            <a href="?page=faq"><img src="<?= baseUrl('public/images/icons/help.png') ?>" alt="FAQ" /></a><br /><br />
            <?PHP

            use App\Support\ForumBridge;
            use App\Support\StringUtil;

            $res = dbquery("
                SELECT
                    COUNT(faq_id)
                FROM
                    " . dbtable('faq') . "
                WHERE
                    faq_deleted=0
                ;");
            $arr = mysql_fetch_row($res);
            echo "<b>" . $arr[0] . "</b> Fragen in der <a href=\"?page=faq\">FAQ</a><br/>zuletzt aktualisiert ";

            $res = dbquery("
                SELECT
                    faq_id,
                    faq_updated
                FROM
                    " . dbtable('faq') . "
                WHERE
                    faq_deleted=0
                ORDER BY
                    faq_updated DESC
                LIMIT
                    1;");
            $arr = mysql_fetch_array($res);
            echo "<a href=\"?page=faq&amp;faq=" . $arr['faq_id'] . "\">" . StringUtil::diffFromNow($arr['faq_updated']) . "</a>";
            ?>
        </td>
        <td>
            <h3>Wiki</h3>
            <a href="?page=article"><img src="<?= baseUrl('public/images/icons/Documents.png') ?>" alt="Wiki" /></a><br /><br />
            <?PHP
            $res = dbquery("
                SELECT count(*) FROM (SELECT hash FROM (SELECT
                    title,
                    changed,
                    id,
                    hash,
                    rev
                FROM
                    " . dbtable('articles') . " a
                ORDER BY
                    title ASC,rev DESC) AS a
                GROUP BY hash) as b
                ;");
            $arr = mysql_fetch_row($res);
            echo "<b>" . $arr[0] . "</b> Artikel im <a href=\"?page=article\">Wiki</a><br/>zuletzt aktualisiert ";
            $res = dbquery("
                SELECT
                    hash,
                    changed
                FROM
                    " . dbtable('articles') . "
                ORDER BY
                    changed DESC
                LIMIT
                    1;");
            $arr = mysql_fetch_array($res);
            echo " <a href=\"?page=article&amp;article=" . $arr['hash'] . "\">" . StringUtil::diffFromNow($arr['changed']) . "</a>";
            ?>
        </td>
        <td>
            <h3>Runden</h3>
            <a href="?page=rounds"><img src="<?= baseUrl('public/images/icons/earth.png') ?>" alt="Runden" /></a><br /><br />
            <?php foreach (App\Models\Round::active() as $round) : ?>
                <a target="_blank" href="<?= loginRoundUrl($round, 'contact') ?>">Kontakt <?= $round->name ?></a><br/>
            <?php endforeach ?>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Forum</h3>
            <a href="<?= ForumBridge::url() ?>"><img src="<?= baseUrl('public/images/icons/chat.png') ?>" alt="Forum" /></a><br /><br />
            <a href="<?= ForumBridge::url('board', 13) ?>">Alles zum Forum</a><br />
            <a href="<?= ForumBridge::url('board', 21) ?>">Technischer Support</a><br />
            <a href="<?= ForumBridge::url('board', 15) ?>">Fragen und Antworten</a><br />
            <a href="<?= ForumBridge::url('team') ?>">Jemanden vom Team kontaktieren</a><br />
            <a href="mailto:<?= get_config('forum_mail') ?>">Mail an die Forenleitung</a>
        </td>
        <td>
            <h3>Entwicklung</h3>
            <a href="https://github.com/etoa/etoa"><img src="<?= baseUrl('public/images/icons/Tools.png') ?>" alt="Entwicklung" /></a><br /><br />
            <a href="https://github.com/etoa/etoa">Entwickler-Portal</a><br />
        </td>
        <td>
            <h3>Weitere Links</h3>
            <a href="<?= baseUrl('archiv') ?>"><img src="<?= baseUrl('public/images/icons/star.png') ?>" alt="Weiteres" /></a><br /><br />
            <a href="<?= baseUrl('archiv') ?>">Downloads</a>
        </td>
    </tr>
</table>
