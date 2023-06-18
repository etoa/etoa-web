<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use App\Repository\TextRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use SlimSession\Helper as SlimSessionHelper;

class TextController extends BackendController
{
    /** @var array<string,array<string,mixed>> */
    private readonly array $templates;

    public function __construct(protected Twig $view, protected SlimSessionHelper $session)
    {
        parent::__construct($view, $session);

        $this->templates = require APP_DIR . '/config/texts.php';
    }

    protected function getTitle(): string
    {
        return 'Text bearbeiten';
    }

    public function index(Request $request, Response $response, TextRepository $texts): Response
    {
        $items = [];
        foreach ($this->templates as $key => $template) {
            $text = $texts->findByKeyword($key);
            $items[$key] = [
                ...$template,
                'id' => $text?->id,
                'lastChanges' => $text?->lastChanges,
            ];
        }

        return parent::render($response, 'texts/index.html', [
            'texts' => $items,
        ]);
    }

    public function edit(Request $request, Response $response, TextRepository $texts, string $key): Response
    {
        $text = $texts->findByKeyword($key);

        return parent::render($response, 'texts/edit.html', [
            'key' => $key,
            'text' => isset($this->templates[$key]) ? [
                ...$this->templates[$key],
                'content' => $text?->content,
            ] : null,
        ]);
    }

    public function update(Request $request, Response $response, TextRepository $texts, string $key): Response
    {
        if (!isset($this->templates[$key])) {
            return $this->redirectToNamedRoute($request, $response, 'admin.texts');
        }

        $post = $request->getParsedBody();
        if (!isset($post['content']) || '' == trim($post['content'])) {
            $this->setSessionMessage('error', 'Inhalt darf nicht leer sein.');

            return $this->redirectToNamedRoute($request, $response, 'admin.texts.edit', ['key' => $key]);
        }

        $text = $texts->findByKeyword($key);
        if (null !== $text) {
            $texts->update($text->id, (string) $post['content']);
        } else {
            $texts->create($key, (string) $post['content']);
        }

        $this->setSessionMessage('success', 'Text gespeichert.');

        return $this->redirectToNamedRoute($request, $response, 'admin.texts');
    }
}
