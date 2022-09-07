<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\Forum\LatestPost;
use App\Repository\ConfigSettingRepository;
use App\Repository\RoundRepository;
use App\Repository\TextRepository;
use App\Support\BBCodeConverter;
use App\Support\ForumBridge;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class FrontendController
{
    public function __construct(
        protected Twig $view,
        private RoundRepository $rounds,
        private TextRepository $texts,
        protected ConfigSettingRepository $config,
        protected ForumBridge $forum,
    ) {
    }

    abstract protected function getTitle(): string;

    abstract protected function getHeaderImage(): string;

    protected function getSiteTitle(): ?string
    {
        return null;
    }

    /**
     * @param array<string,mixed> $args
     */
    protected function render(Response $response, string $frontendTemplate, array $args = []): Response
    {
        return $this->view->render(
            $response,
            'frontend/' . $frontendTemplate,
            array_merge([
                'title' => $this->getTitle(),
                'site_title' => $this->getSiteTitle(),
                'header_img' => $this->getHeaderImage(),
                'votebanner' => $this->config->get('buttons'),
                'adds' => $this->config->get('adds'),
                'footerJs' => $this->config->get('footer_js'),
                'headerJs' => $this->config->get('indexjscript'),
                'mainMenu' => $this->getMainMenu(),
                'gameLogin' => $this->getGameLogin(),
                'forumStatus' => $this->getForumStatus(),
                'serverNotice' => $this->getServerNotice(),
            ], $args)
        );
    }

    protected function getTextContent(string $keyword): ?string
    {
        $text = $this->texts->findByKeyword($keyword);
        if (null !== $text) {
            if ('' != $text->content) {
                return BBCodeConverter::toHtml($text->content);
            }
        }

        $templates = require APP_DIR . '/config/texts.php';

        return isset($templates[$keyword]) ? $templates[$keyword]->default : null;
    }

    private function getGameLogin(): string
    {
        $t = time();
        $logintoken = sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $t) . dechex($t);

        return $this->view->fetch('frontend/widgets/game_login.html', [
            'loginform' => [
                'logintoken' => $logintoken,
                'nickField' => sha1('nick' . $logintoken . $t),
                'passwordField' => sha1('password' . $logintoken . $t),
                'rnd' => mt_rand(10000, 99999),
            ],
            'rounds' => $this->rounds->active(),
            'selectedRound' => isset($_COOKIE['round']) ? $_COOKIE['round'] : '',
        ]);
    }

    private function getMainMenu(): string
    {
        $tsLink = $this->config->get('ts_link');
        $items = [
            [
                'type' => 'route',
                'route' => 'news',
                'label' => 'News',
            ],
            [
                'type' => 'route',
                'route' => 'features',
                'label' => 'Über EtoA',
            ],
            [
                'type' => 'route',
                'route' => 'screenshots',
                'label' => 'Bilder',
            ],
            [
                'type' => 'route',
                'route' => 'story',
                'label' => 'Story',
            ],
            [
                'type' => 'route',
                'route' => 'rules',
                'label' => 'Regeln',
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'route',
                'route' => 'register',
                'label' => 'Mitspielen',
            ],
            [
                'type' => 'route',
                'route' => 'pwrequest',
                'label' => 'Passwort vergessen?',
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'url',
                'url' => ForumBridge::url(),
                'label' => 'Forum',
            ],
            !empty($tsLink) ? [
                'type' => 'url',
                'url' => $tsLink,
                'label' => 'Discord',
            ] : null,
            [
                'type' => 'url',
                'url' => 'files',
                'label' => 'Downloads',
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'route',
                'route' => 'banner',
                'label' => 'Weitersagen',
            ],
            [
                'type' => 'route',
                'route' => 'donate',
                'label' => 'Spenden',
            ],
            [
                'type' => 'route',
                'route' => 'disclaimer',
                'label' => 'Disclaimer',
            ],
            [
                'type' => 'route',
                'route' => 'privacy',
                'label' => 'Datenschutz',
            ],
            [
                'type' => 'route',
                'route' => 'imprint',
                'label' => 'Impressum',
            ],
        ];

        return $this->view->fetch('frontend/widgets/main_menu.html', [
            'nav' => array_filter($items, fn ($i) => null !== $i),
        ]);
    }

    private function getForumStatus(): string
    {
        if (!$data = apcu_fetch('etoa-infobox-forum-news')) {
            $num_posts = $this->config->getInt('latest_posts_num', 5);
            $data = [];
            try {
                $data['users_online'] = $this->forum->usersOnline();
            } catch (\Doctrine\DBAL\Exception $ignored) {
            }
            try {
                $board_blacklist = array_map(fn ($e) => (int) $e, explode(',', $this->config->get('infobox_board_blacklist')));
                $posts = $this->forum->latestPosts($num_posts, $board_blacklist);
                $data['posts'] = array_map(
                    fn (LatestPost $post) => [
                        'topic' => $post->topic,
                        'time' => $post->time,
                        'url' => ForumBridge::url('post', $post->id, $post->thread_id),
                    ],
                    $posts
                );
            } catch (\Doctrine\DBAL\Exception $ignored) {
            }
            apcu_add('etoa-infobox-forum-news', $data, config('caching.apcu_timeout'));
        }

        return $this->view->fetch('frontend/widgets/forum_status.html', $data);
    }

    private function getServerNotice(): string
    {
        $server_notice = $this->config->get('server_notice');

        $data = '' != $server_notice ? [
            'message' => $server_notice,
            'color' => $this->config->get('server_notice_color', '#fff'),
            'updated' => $this->config->get('server_notice_updated'),
        ] : [];

        return $this->view->fetch('frontend/widgets/server_notice.html', $data);
    }
}
