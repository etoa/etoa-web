<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Support\ForumBridge;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NewsController extends FrontendController
{
    protected function getTitle(): string
    {
        return 'News';
    }

    protected function getHeaderImage(): string
    {
        return 'news.png';
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $news_board_id = $this->config->getInt('news_board');

        return parent::render($response, 'news.html', [
            'text' => $this->getTextContent("home"),
            'news' => $this->fetchNews($news_board_id),
            'board_url' => ForumBridge::url('board', $news_board_id),
        ]);
    }

    private function fetchNews(int $news_board_id): ?array
    {
        if (!$news = apcu_fetch('etoa-news-section')) {
            $status_board_id = $this->config->getInt('status_board');
            $num_news = $this->config->getInt('news_posts_num', 3);
            try {
                $threads = ForumBridge::newsPosts($num_news, $news_board_id, $status_board_id);
                $news = array_map(fn (array $thread) => [
                    'prefix' => $thread['board_id'] == $status_board_id ? "SERVERSTATUS " : "",
                    'url' => ForumBridge::url('thread', $thread['id']),
                    'topic' => $thread['topic'],
                    'closed' => $thread['board_id'] == $status_board_id && $thread['closed'] == 1,
                    'last_post_time' => $thread['last_post_time'],
                    'time' => $thread['time'],
                    'updated_at' => $thread['updated_at'],
                    'author_url' => ForumBridge::url('user', $thread['user_id']),
                    'author' => $thread['user_name'],
                    'message' =>  $thread["message"],
                    'replies' => $thread['post_count'] - 1,
                ], $threads);
                apcu_add('etoa-news-section', $news, config('caching.apcu_timeout'));
            } catch (PDOException $ignored) {
                return null;
            }
        }
        return $news;
    }
}
