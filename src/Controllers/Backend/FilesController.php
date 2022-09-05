<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\StorageAttributes;
use League\Flysystem\UnableToDeleteFile;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FilesController extends BackendController
{
    protected function getTitle(): string
    {
        return 'Dateimanager';
    }

    private function getAdapter(): Filesystem
    {
        $adapter = new LocalFilesystemAdapter(APP_DIR . '/public/pub');
        $filesystem = new Filesystem($adapter);

        return $filesystem;
    }

    public function index(Request $request, Response $response): Response
    {
        $detector = new FinfoMimeTypeDetector();
        $filesystem = $this->getAdapter();

        /** @var string[] $files */
        $files = $filesystem->listContents('/')
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile() && !str_starts_with($attributes->path(), '.'))
            ->sortByPath()
            ->map(fn (StorageAttributes $attributes) => [
                'path' => $attributes->path(),
                'lastModified' => $attributes->lastModified(),
                'mimeType' => $detector->detectMimeTypeFromPath($attributes->path()),
                'fileSize' => $filesystem->fileSize($attributes->path()),
                'url' => '/pub/' . $attributes->path(),
            ])
            ->toArray();

        return parent::render($response, 'files/index.html', [
            'files' => $files,
        ]);
    }

    public function confirmDelete(Request $request, Response $response): Response
    {
        $path = $request->getQueryParams()['path'] ?? null;

        return parent::render($response, 'files/delete.html', [
            'path' => null !== $path && $this->getAdapter()->fileExists($path) ? $path : null,
        ]);
    }

    public function destroy(Request $request, Response $response): Response
    {
        $path = $request->getQueryParams()['path'] ?? null;

        if (null === $path || !$this->getAdapter()->fileExists($path)) {
            $this->setSessionMessage('error', 'Datei nicht vorhanden.');

            return $this->redirectToNamedRoute($request, $response, 'admin.files');
        }

        try {
            $this->getAdapter()->delete($path);
            $this->setSessionMessage('success', 'Datei gelöscht.');
        } catch (FilesystemException|UnableToDeleteFile $exception) {
            $this->setSessionMessage('error', 'Datei konnte nicht gelöscht werden: ' + $exception->getMessage());
        }

        return $this->redirectToNamedRoute($request, $response, 'admin.files');
    }
}
