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
use Slim\Views\Twig;

class FilesController extends BackendController
{
    private const ROOT_DIR = '/pub';

    private Filesystem $filesystem;

    public function __construct(protected Twig $view, protected \SlimSession\Helper $session)
    {
        parent::__construct($view, $session);
        $adapter = new LocalFilesystemAdapter(APP_DIR . '/public' . self::ROOT_DIR);
        $this->filesystem = new Filesystem($adapter);
    }

    protected function getTitle(): string
    {
        return 'Dateimanager';
    }

    public function index(Request $request, Response $response): Response
    {
        $detector = new FinfoMimeTypeDetector();

        /** @var string[] $files */
        $files = $this->filesystem->listContents('/')
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile() && !str_starts_with($attributes->path(), '.'))
            ->sortByPath()
            ->map(fn (StorageAttributes $attributes) => [
                'path' => $attributes->path(),
                'lastModified' => $attributes->lastModified(),
                'mimeType' => $detector->detectMimeTypeFromPath($attributes->path()),
                'fileSize' => $this->filesystem->fileSize($attributes->path()),
                'url' => self::ROOT_DIR . '/' . $attributes->path(),
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
            'path' => null !== $path && $$this->filesystem->fileExists($path) ? $path : null,
        ]);
    }

    public function destroy(Request $request, Response $response): Response
    {
        $path = $request->getQueryParams()['path'] ?? null;

        if (null === $path || !$this->filesystem->fileExists($path)) {
            $this->setSessionMessage('error', 'Datei nicht vorhanden.');

            return $this->redirectToNamedRoute($request, $response, 'admin.files');
        }

        try {
            $this->filesystem->delete($path);
            $this->setSessionMessage('success', 'Datei gelöscht.');
        } catch (FilesystemException | UnableToDeleteFile $exception) {
            $this->setSessionMessage('error', 'Datei konnte nicht gelöscht werden: ' + $exception->getMessage());
        }

        return $this->redirectToNamedRoute($request, $response, 'admin.files');
    }
}
