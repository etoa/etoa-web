<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\StorageAttributes;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FilesController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Dateimanager';
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $adapter = new LocalFilesystemAdapter(APP_DIR . '/public/pub');
        $filesystem = new Filesystem($adapter);
        $detector = new FinfoMimeTypeDetector();

        /** @var string[] $files */
        $files = $filesystem->listContents('/')
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile() && !str_starts_with($attributes->path(), '.'))
            ->sortByPath()
            ->map(fn (StorageAttributes $attributes) => [
                'path' => $attributes->path(),
                'lastModified' => $attributes->lastModified(),
                'mimeType' => $detector->detectMimeTypeFromPath($attributes->path()),
                'url' => '/pub/' . $attributes->path(),
            ])
            ->toArray();

        return parent::render($response, 'files.html', [
            'files' => $files,
        ]);
    }
}
