<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Controllers\AbstractController;
use App\Models\Forum\LatestPost;
use App\Repository\ConfigSettingRepository;
use App\Repository\TextRepository;
use App\Support\BBCodeConverter;
use App\Support\ForumBridge;
use App\Support\GameLoginFormService;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class FrontendController extends AbstractController
{
    public function __construct(
        protected Twig $view,
        private TextRepository $texts,
        protected ConfigSettingRepository $config,
        protected ForumBridge $forum,
        private GameLoginFormService $loginForm,
    ) {
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
        return BBCodeConverter::toHtml($this->texts->getContent($keyword));
    }

    private function getGameLogin(): string
    {
        return $this->view->fetch('frontend/widgets/game_login.html', [
            'loginform' => $this->loginForm->createLoginFormData(),
            'rounds' => $this->loginForm->getRounds(),
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
                'route' => 'about',
                'label' => 'Über EtoA',
            ],
            [
                'type' => 'route',
                'route' => 'screenshots',
                'label' => 'Bilder',
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
                'url' => 'archiv',
                'label' => 'Downloads',
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'route',
                'route' => 'donate',
                'label' => 'Unterstütze uns',
            ],
            [
                'type' => 'route',
                'route' => 'legal',
                'label' => 'Rechtliches',
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
                $posts = $this->forum->latestPosts($num_posts);
                $data['posts'] = array_map(
                    fn (LatestPost $post) => [
                        'topic' => $post->topic,
                        'time' => $post->time,
                        'username' => $post->username,
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
