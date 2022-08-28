<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Support\ForumBridge;
use App\Support\StringUtil;
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

    function __invoke(Request $request, Response $response): Response
    {
        $news_board_id = $this->config->getInt('news_board');
        $status_board_id = $this->config->getInt('status_board');
        $num_news = 3;

        $message = null;
        $news = [];
        if (!$news = apcu_fetch('etoa-news-section')) {
            try {
                $threads = ForumBridge::newsPosts($num_news, $news_board_id, $status_board_id);
                $news = array_map(fn (array $thread) => [
                    'prefix' => $thread['board_id'] == $status_board_id ? "SERVERSTATUS " : "",
                    'url' => ForumBridge::url('thread', $thread['id']),
                    'topic' => $thread['topic'],
                    'sufix' => $thread['board_id'] == $status_board_id && $thread['closed'] == 1 ? "Abgeschlossen (" . StringUtil::dateFormat($thread['lastposttime']) . ")" : '',
                    'date' => StringUtil::dateFormat($thread['time']),
                    'author_url' => ForumBridge::url('user', $thread['user_id']),
                    'author' => $thread['user_name'],
                    'author_suffix' => $thread['updated_at'] > 0 ? " (Letzte Änderung: " . StringUtil::dateFormat($thread['updated_at']) . ")" : '',
                    'message' =>  $thread["message"],
                    'replies' => $thread['post_count'] > 1 ? (($thread['post_count'] - 1) . ' Kommentare vorhanden') : 'Kommentiere diese Nachricht',
                ], $threads);
                apcu_add('etoa-news-section', $news, config('caching.apcu_timeout'));
            } catch (PDOException $ignored) {
                $message = 'Der Newsfeed ist momentan nicht verfügbar!';
            }
        }

        return parent::render($response, 'news.html', [
            'text' => $this->getTextContent("home"),
            'news' => $news,
            'board_url' => ForumBridge::url('board', $news_board_id),
            'message' => $message,
        ]);
    }
}
