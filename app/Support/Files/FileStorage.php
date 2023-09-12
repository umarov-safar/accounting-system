<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpParamsInspection */

namespace App\Support\Files;

use App\Exceptions\FileStorageException;
use Illuminate\Filesystem\FilesystemAdapter;

class FileStorage
{
    public function __construct(private FilesystemAdapter $filesystem)
    {
    }

    public function add(FileIntent $intent): string
    {
        $fileName = $intent->getName();

        return throw_unless(
            $this->filesystem->putFileAs($intent->getDirectory(), $intent->getSource(), $fileName),
            FileStorageException::class,
            "Unable to save file {$fileName} to directory {$intent->getDirectory()}"
        );
    }

    public function remove(string $filePath): void
    {
        if (!$this->filesystem->exists($filePath)) {
            return;
        }

        throw_unless(
            $this->filesystem->delete($filePath),
            FileStorageException::class,
            "Failed to delete file {$filePath}"
        );
    }
}
