<?php

namespace App\Domain\Support\Actions;

use App\Domain\Support\Concerns\AppliesToAggregate;
use App\Domain\Support\Data\ReservedFile;
use App\Domain\Support\Models\Model;
use App\Domain\Support\Models\TempFile;
use Ensi\LaravelEnsiFilesystem\EnsiFilesystemManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReserveFileAction
{
    use AppliesToAggregate;

    public function __construct(private readonly EnsiFilesystemManager $fileManager)
    {
    }

    /**
     * @param TempFile $prototype
     * @param int $count
     * @return Collection<ReservedFile>
     */
    public function execute(TempFile $prototype, int $count): Collection
    {
        ['folder' => $folder, 'extension' => $extension] = $this->getFileInfo($prototype->path);

        return Collection::times($count, function () use ($prototype, $folder, $extension) {
            $path = $this->makeFilePath($folder, $extension);
            $tempFile = $this->createTempFile($path);

            return new ReservedFile($tempFile, $prototype);
        });
    }

    private function makeFilePath(string $folder, string $extension): string
    {
        $hash = Str::random(20);
        $fileName = "{$hash}.{$extension}";
        $hashedSubDirs = $this->fileManager->getHashedDirsForFileName($fileName);

        return blank($folder)
            ? "{$hashedSubDirs}/{$fileName}"
            : "{$folder}/{$hashedSubDirs}/{$fileName}";
    }

    private function getFileInfo(string $path): array
    {
        $parts = pathinfo($path);
        $subDirs = $this->fileManager->getHashedDirsForFileName($parts['basename']);
        $folder = str_replace("/{$subDirs}", '', $parts['dirname']);

        return [
            'folder' => $folder,
            'extension' => $parts['extension'],
        ];
    }

    private function createTempFile(string $path): TempFile
    {
        return $this->create(['path' => $path]);
    }

    protected function createModel(): Model
    {
        return new TempFile();
    }
}
