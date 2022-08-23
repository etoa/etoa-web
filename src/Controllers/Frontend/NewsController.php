<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Support\ForumBridge;
use App\Support\StringUtil;
use App\Support\TextUtil;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class NewsController
{
    function __invoke(Request $request, Response $response, Twig $view): Response
    {
        $news_board_id = intval(get_config('news_board'));
        $status_board_id = intval(get_config('status_board'));
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

        return $view->render($response, 'frontend/news.html', [
            'site_title' => 'News',
            'header_img' => 'news.png',
            'text' => TextUtil::get("home"),
            'news' => $news,
            'board_url' => ForumBridge::url('board', $news_board_id),
            'message' => $message,
        ]);
    }
}
