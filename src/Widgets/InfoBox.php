<?php

namespace App\Widgets;

use App\Service\ConfigService;
use App\Support\ForumBridge;
use App\Support\StringUtil;
use PDOException;
use Slim\Views\Twig;

class InfoBox implements Widget
{
    const LATEST_POSTS_NUM = 5;

    function __construct(private ConfigService $config)
    {
    }

    public function render(Twig $view): string
    {
        ob_start();
        $this->forum();
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
                $board_blacklist = explode(",", $this->config->get('infobox_board_blacklist'));
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

    private function serverNotice()
    {
        $server_notice = $this->config->get('server_notice');
        if ($server_notice != "") {
            $color = $this->config->get('server_notice_color', "#fff");
            echo "<br/><br><div style=\"border:1px solid " . $color . ";padding:4px;background:#223;color:" . $color . "\">";
            echo StringUtil::text2html($server_notice);
            echo "<br/><div style=\"margin-top:5px;font-size:8pt;\">Aktualisiert: " . StringUtil::dateFormat($this->config->get('server_notice_updated')) . "</div>";
            echo "</div><br/>";
        }
    }
}
