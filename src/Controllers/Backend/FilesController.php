<?php

declare(strict_types=1);

namespace App\Controllers\Backend;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemReader;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\StorageAttributes;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Views\Twig;

class FilesController extends BackendController
{
    private const DIR = '/files';
    private const BASE_PATH = APP_DIR . '/public' . self::DIR;
    private const PUB_PATH = self::DIR;

    private Filesystem $filesystem;

    public function __construct(protected Twig $view, protected \SlimSession\Helper $session, private Logger $log)
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
        $files = $this->filesystem->listContents('/', FilesystemReader::LIST_DEEP)
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
            $this->log->info('Uploaded file ' . $name);
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

    public function edit(Request $request, Response $response): Response
    {
        $path = $request->getQueryParams()['path'] ?? null;

        return parent::render($response, 'files/edit.html', [
            'path' => null !== $path && $this->filesystem->fileExists($path) ? $path : null,
        ]);
    }

    public function update(Request $request, Response $response): Response
    {
        $path = $request->getQueryParams()['path'] ?? null;

        if (null === $path || !$this->filesystem->fileExists($path)) {
            $this->setSessionMessage('error', 'Datei nicht vorhanden.');

            return $this->redirectToNamedRoute($request, $response, 'admin.files');
        }

        $post = $request->getParsedBody();
        if (!isset($post['name']) || '' == trim($post['name'])) {
            $this->setSessionMessage('error', 'Name darf nicht leer sein.');

            return $this->redirectToNamedRoute($request, $response, 'admin.files.edit', queryParams: ['path' => $path]);
        }

        $destination = $post['name'];
        if ($path == $destination) {
            return $this->redirectToNamedRoute($request, $response, 'admin.files');
        }
        if ($this->filesystem->has($destination)) {
            $this->setSessionMessage('error', 'Dieser Name existiert bereits.');

            return $this->redirectToNamedRoute($request, $response, 'admin.files.edit', queryParams: ['path' => $path]);
        }

        try {
            $this->filesystem->move($path, $destination);
            $this->log->info('Renamed file ' . $path . ' to ' . $destination);
            $this->deleteEmptyDirecytories();
            $this->setSessionMessage('success', 'Datei umbenannt.');
        } catch (FilesystemException | UnableToMoveFile $exception) {
            $this->setSessionMessage('error', 'Datei konnte nicht umbenannt werden: ' . $exception->getMessage());
        }

        return $this->redirectToNamedRoute($request, $response, 'admin.files');
    }

    private function deleteEmptyDirecytories(): void
    {
        $deleted = -1;
        while (0 != $deleted) {
            $deleted = 0;
            /** @var string[] $directories */
            $directories = $this->filesystem->listContents('/', Filesystem::LIST_DEEP)
                ->filter(fn (StorageAttributes $attributes) => $attributes->isDir())
                ->map(fn (StorageAttributes $attributes) => $attributes->path())
                ->toArray();
            foreach ($directories as $dir) {
                $isEmpty = 0 == count($this->filesystem->listContents($dir)->toArray());
                if ($isEmpty) {
                    try {
                        $this->filesystem->deleteDirectory($dir);
                        ++$deleted;
                        $this->log->debug('Deleted directory ' . $dir);
                    } catch (FilesystemException | UnableToDeleteDirectory $exception) {
                        $this->log->error('Unable to delete directory ' . $dir . ': ' . $exception->getMessage());
                    }
                }
            }
        }
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
            $this->log->info('Deleted file ' . $path);
            $this->deleteEmptyDirecytories();
            $this->setSessionMessage('success', 'Datei gelöscht.');
        } catch (FilesystemException | UnableToDeleteFile $exception) {
            $this->setSessionMessage('error', 'Datei konnte nicht gelöscht werden: ' . $exception->getMessage());
        }

        return $this->redirectToNamedRoute($request, $response, 'admin.files');
    }
}
