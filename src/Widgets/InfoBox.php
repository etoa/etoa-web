<?php

namespace App\Widgets;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Tag;
use App\Support\ForumBridge;
use App\Support\StringUtil;
use App\TemplateEngine;
use PDOException;

class InfoBox implements Widget
{
    const LATEST_POSTS_NUM = 5;
    const FAQ_NUM = 5;
    const TAG_NUM = 10;

    public function render(TemplateEngine $tpl): string
    {
        ob_start();
        $this->forum();
        $this->helpCenter();
        $this->tags();
        $this->serverNotice();
        return ob_get_clean();
    }

    private function forum()
    {
        echo "<h2>Neues aus dem Forum</h2>";
        if (!$formumNews = apcu_fetch('etoa-infobox-forum-news')) {
            ob_start();
            try {
                echo "<span style=\"color:#0f0;font-size:9pt;\">" . ForumBridge::usersOnline() . " Leute online</span>";
            } catch (PDOException $ignored) {
                echo "<span style=\"color:#f00;font-size:9pt;\">Status nicht verfügbar</span>";
            }
            try {
                $board_blacklist = explode(",", get_config('infobox_board_blacklist'));
                $posts = ForumBridge::latestPosts(self::LATEST_POSTS_NUM, $board_blacklist);
                echo "<div id=\"forum\" style=\"\">
                <ul id=\"forumthreadlist\">";
                foreach ($posts as $post) {
                    echo "<li>
                    <a href=\"" . ForumBridge::url('post', $post['id'], $post['thead_id']) . "\">" . $post['topic'] . "</a>
                    <span style=\"color:#aaa;font-size:80%\">" . StringUtil::diffFromNow($post['time']) . "</span>
                    </li>";
                }
                echo "</ul></div>";
            } catch (PDOException $ignored) {
                echo '<p>Die letzten Beiträge sind zurzeit nicht verfügbar.</p>';
            }
            $formumNews = ob_get_clean();
            apcu_add('etoa-infobox-forum-news', $formumNews, config('caching.apcu_timeout'));
        }
        echo $formumNews;
    }

    private function helpCenter()
    {
        echo "<br/><h2>Hilfecenter</h2>
        <span style=\"color:#0f0;font-size:9pt;\">" . (Faq::countActive() + Article::count()) . " Einträge</span><br/>";
        echo "<ul id=\"helplist\">";
        foreach (Faq::latest(self::FAQ_NUM) as $faq) {
            $txt = StringUtil::text2html($faq->question);
            if (strlen($txt) > 30) {
                $txt = substr($txt, 0, 24) . "...";
            }
            echo '<li><a href="' . helpUrl('faq', 'faq', $faq->id) . '">' . $txt . '</a></li>';
        }
        echo "</ul>";
    }

    private function tags()
    {
        echo "<b>Tags:</b><br/>";
        foreach (Tag::popular(self::TAG_NUM) as $tag) {
            echo '<a href="' . helpUrl('tags', 'id', $tag->id) . '" title="' . $tag->count . '">' . $tag->name . '</a> ';
        }
    }

    private function serverNotice()
    {
        $server_notice = get_config('server_notice');
        if ($server_notice != "") {
            $color = get_config('server_notice_color', "#fff");
            echo "<br/><br><div style=\"border:1px solid " . $color . ";padding:4px;background:#223;color:" . $color . "\">";
            echo StringUtil::text2html($server_notice);
            echo "<br/><div style=\"margin-top:5px;font-size:8pt;\">Aktualisiert: " . StringUtil::dateFormat(get_config('server_notice_updated')) . "</div>";
            echo "</div><br/>";
        }
    }
}
