<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\UI\TextBlock;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class TextPageController extends FrontendController
{
    /**
     * @return TextBlock[]
     */
    abstract protected function getTextBlocks(): array;

    public function __invoke(Request $request, Response $response): Response
    {
        return parent::render($response, 'text-page.html', [
            'textblocks' => $this->getTextBlocks(),
        ]);
    }
}
