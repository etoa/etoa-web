<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\ContentBlock;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

abstract class ContentPageController extends FrontendController
{
    abstract protected function getSiteTitle(): ?string;

    abstract protected function getHeaderImage(): string;

    /**
     * @return ContentBlock[]
     */
    abstract protected function getBlocks(): array;

    public function __invoke(Request $request, Response $response, Twig $twig): Response
    {
        return parent::render($response, 'content-page.html', [
            'site_title' => $this->getSiteTitle(),
            'header_img' => $this->getHeaderImage(),
            'content_blocks' => array_map(fn (ContentBlock $block) => $block->render($twig), $this->getBlocks()),
        ]);
    }
}
