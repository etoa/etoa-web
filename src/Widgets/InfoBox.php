<?php

namespace App\Widgets;

use App\Service\ConfigService;
use App\Support\ForumBridge;
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
        return $view->fetch('frontend/widgets/infobox.html', [
            'forum' => $this->getForumStatus(),
            'server_notice' => $this->getServerNotice(),
        ]);
    }

    private function getForumStatus(): array
    {
        if (!$data = apcu_fetch('etoa-infobox-forum-news')) {
            $data = [];
            try {
                $data['users_online'] = ForumBridge::usersOnline();
            } catch (PDOException $ignored) {
            }
            try {
                $board_blacklist = explode(",", $this->config->get('infobox_board_blacklist'));
                $posts = ForumBridge::latestPosts(self::LATEST_POSTS_NUM, $board_blacklist);
                $data['posts'] = array_map(
                    fn (array $post) => array_merge($post, [
                        'url' => ForumBridge::url('post', $post['id'], $post['thead_id']),
                    ]),
                    $posts
                );
            } catch (PDOException $ignored) {
            }
            apcu_add('etoa-infobox-forum-news', $data, config('caching.apcu_timeout'));
        }
        return $data;
    }

    private function getServerNotice(): ?array
    {
        $server_notice = $this->config->get('server_notice');
        if ($server_notice == '') {
            return null;
        }

        return [
            'message' => $server_notice,
            'color' => $this->config->get('server_notice_color', "#fff"),
            'updated' => $this->config->get('server_notice_updated'),
        ];
    }
}
