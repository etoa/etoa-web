<?php

namespace App\UI;

use Slim\Views\Twig;

class TextBlock implements ContentBlock
{
    public function __construct(public string $title, public string $content)
    {
    }

    public function render(Twig $twig): string
    {
        return $twig->fetch('components/text-block.html', [
            'title' => $this->title,
            'content' => $this->content,
        ]);
    }
}
