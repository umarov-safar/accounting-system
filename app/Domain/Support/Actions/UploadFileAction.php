<?php

namespace App\Domain\Support\Actions;

use App\Domain\Support\Concerns\AppliesToAggregate;
use App\Domain\Support\Models\Model;
use App\Domain\Support\Models\TempFile;
use Ensi\LaravelEnsiFilesystem\EnsiFilesystemManager;
use Exception;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class UploadFileAction
{
    use AppliesToAggregate;

    public function __construct(private EnsiFilesystemManager $fileManager)
    {
    }

    public function execute(UploadedFile $file, string $folder, string $fileNamePrefix = ''): TempFile
    {
        $fileName = $this->makeFileName($file, $fileNamePrefix);
        $path = $this->saveFile($file, $fileName, $folder);

        try {
            return $this->createTempFile($path);
        } catch (Exception $e) {
            $this->getDisk()->delete($path);

            throw $e;
        }
    }

    private function saveFile(UploadedFile $file, string $fileName, string $folder): string
    {
        $hashedSubDirs = $this->fileManager->getHashedDirsForFileName($fileName);
        $destPath = blank($folder) ? $hashedSubDirs : "$folder/{$hashedSubDirs}";

        $path = $this->getDisk()->putFileAs($destPath, $file, $fileName);

        if (!$path) {
            throw new RuntimeException("Не удалось запись файл $fileName в каталог $destPath");
        }

        return $path;
    }

    private function makeFileName(UploadedFile $file, string $fileNamePrefix): string
    {
        $hash = Str::random(20);
        $extension = $this->extractExtension($file);

        return blank($fileNamePrefix)
            ? "{$hash}.{$extension}"
            : "{$fileNamePrefix}_{$hash}.{$extension}";
    }

    private function extractExtension(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        if (filled($extension)) {
            return $extension;
        }

        $extension = $file->extension();
        if (filled($extension)) {
            return $extension;
        }

        return 'bin';
    }

    private function getDisk(): FilesystemAdapter
    {
        return Storage::disk($this->fileManager->publicDiskName());
    }

    private function createTempFile(string $path): TempFile
    {
        return $this->updateOrCreate(null, function (TempFile $file) use ($path) {
            $file->path = $path;
        });
    }

    protected function createModel(): Model
    {
        return new TempFile();
    }
}
