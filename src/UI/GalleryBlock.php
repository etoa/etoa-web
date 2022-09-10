<?php

namespace App\UI;

use Slim\Views\Twig;

class GalleryBlock implements ContentBlock
{
    /**
     * @param array<integer,array<string,string>> $images
     */
    public function __construct(public string $title, public array $images)
    {
    }

    public function render(Twig $twig): string
    {
        return $twig->fetch('components/gallery-block.html', [
            'title' => $this->title,
            'images' => $this->images,
        ]);
    }
}
