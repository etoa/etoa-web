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
use Psr\Http\Message\UploadedFileInterface;
use Slim\Views\Twig;

class FilesController extends BackendController
{
    private const DIR = '/pub';
    private const BASE_PATH = APP_DIR . '/public' . self::DIR;
    private const PUB_PATH = self::DIR;

    private Filesystem $filesystem;

    public function __construct(protected Twig $view, protected \SlimSession\Helper $session)
    {
        parent::__construct($view, $session);
        $adapter = new LocalFilesystemAdapter(self::BASE_PATH);
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
                'url' => self::PUB_PATH . '/' . $attributes->path(),
            ])
            ->toArray();

        return parent::render($response, 'files/index.html', [
            'files' => $files,
            'maxFileSize' => $this->getMaxFileSize(),
        ]);
    }

    private function getMaxFileSize(): int
    {
        $max_upload = (int) ini_get('upload_max_filesize');
        $max_post = (int) ini_get('post_max_size');
        $memory_limit = (int) ini_get('memory_limit');

        return min($max_upload, $max_post, $memory_limit);
    }

    public function upload(Request $request, Response $response): Response
    {
        $uploadedFiles = $request->getUploadedFiles();

        /** @var UploadedFileInterface $uploadedFile */
        $uploadedFile = $uploadedFiles['file'];
        if (UPLOAD_ERR_OK === $uploadedFile->getError()) {
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $filename = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
            $name = self::cleanName($filename) . '.' . strtolower($extension);
            $uploadedFile->moveTo(self::BASE_PATH . DIRECTORY_SEPARATOR . $name);
            $this->setSessionMessage('success', "Datei '$name' hochgeladen.");
        } else {
            $this->setSessionMessage('success', 'Datei konnte nicht hochgeladen werden: ' . $uploadedFile->getError());
        }

        return $this->redirectToNamedRoute($request, $response, 'admin.files');
    }

    private static function cleanName(string $string): string
    {
        return trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', $string), '-');
    }

    public function confirmDelete(Request $request, Response $response): Response
    {
        $path = $request->getQueryParams()['path'] ?? null;

        return parent::render($response, 'files/delete.html', [
            'path' => null !== $path && $this->filesystem->fileExists($path) ? $path : null,
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
            $this->setSessionMessage('error', 'Datei konnte nicht gelöscht werden: ' . $exception->getMessage());
        }

        return $this->redirectToNamedRoute($request, $response, 'admin.files');
    }
}
