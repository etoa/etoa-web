<?php

namespace App\UI;

use Slim\Views\Twig;

class CustomTemplateBlock implements ContentBlock
{
    /**
     * @param array<string,mixed> $data
     */
    public function __construct(public string $template, public array $data = [])
    {
    }

    public function render(Twig $twig): string
    {
        return $twig->fetch($this->template, $this->data);
    }
}
