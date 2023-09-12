<?php

use App\Domain\Support\Actions\UploadFileAction;
use Ensi\LaravelEnsiFilesystem\EnsiFilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\IntegrationTestCase;

uses(IntegrationTestCase::class)->group('support', 'integration');

test('upload success', function () {
    $fs = resolve(EnsiFilesystemManager::class);
    Storage::fake($fs->publicDiskName());

    $uploadedFile = UploadedFile::fake()->create("image_01.png", kilobytes: 20);
    $action = new UploadFileAction($fs);

    $tempFile = $action->execute($uploadedFile, '');

    Storage::disk($fs->publicDiskName())->assertExists($tempFile->path);
});

test('upload with prefix', function () {
    $fs = resolve(EnsiFilesystemManager::class);
    Storage::fake($fs->publicDiskName());

    $uploadedFile = UploadedFile::fake()->create("image_02.webp", kilobytes: 20);
    $action = new UploadFileAction($fs);

    $tempFile = $action->execute($uploadedFile, '', 'foo');

    expect($tempFile->path)->toContain('foo_');
});

test('upload without extension', function () {
    $fs = resolve(EnsiFilesystemManager::class);
    Storage::fake($fs->publicDiskName());

    $uploadedFile = UploadedFile::fake()->create("data", kilobytes: 20, mimeType: 'image/jpeg');
    $action = new UploadFileAction($fs);

    $tempFile = $action->execute($uploadedFile, '');

    expect($tempFile->path)->toContain('.jpg');
});

test('upload to folder', function () {
    $fs = resolve(EnsiFilesystemManager::class);
    Storage::fake($fs->publicDiskName());

    $uploadedFile = UploadedFile::fake()->create("image_03.gif", kilobytes: 20);
    $action = new UploadFileAction($fs);

    $tempFile = $action->execute($uploadedFile, 'foo');

    expect($tempFile->path)->toContain('foo/');
});
