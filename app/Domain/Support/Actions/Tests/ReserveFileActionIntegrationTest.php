<?php

use App\Domain\Support\Actions\ReserveFileAction;
use App\Domain\Support\Data\ReservedFile;
use App\Domain\Support\Models\TempFile;
use Ensi\LaravelEnsiFilesystem\EnsiFilesystemManager;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertTrue;

use Tests\IntegrationTestCase;

uses(IntegrationTestCase::class)->group('support', 'integration');

test('Reserve success', function () {
    $fs = resolve(EnsiFilesystemManager::class);
    $filename = "image.png";
    $subDirs = $fs->getHashedDirsForFileName($filename);
    $path = "products/{$subDirs}/{$filename}";

    $prototype = TempFile::create(['path' => $path]);
    $count = 2;

    $action = new ReserveFileAction($fs);
    $files = $action->execute($prototype, $count);

    assertCount($count, $files);

    /** @var ReservedFile $reserved */
    $reserved = $files->pop();

    assertNotEquals($reserved->copy->path, $path);

    assertEquals(
        pathinfo($reserved->copy->path, PATHINFO_EXTENSION),
        pathinfo($path, PATHINFO_EXTENSION)
    );

    assertTrue(str_starts_with($reserved->copy->path, 'products'));
});
