<?php

namespace App\Support\Files;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileIntent
{
    private string $uid;

    private UploadedFile $source;

    private ?string $directory;

    private ?string $namePrefix;

    public function __construct(UploadedFile $source)
    {
        $this->source = $source;
        $this->uid = Str::random(20);
    }

    public static function create(UploadedFile $source): self
    {
        return new static($source);
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getSource(): UploadedFile
    {
        return $this->source;
    }

    public function getName(): string
    {
        $extension = $this->source->extension();
        if (blank($extension)) {
            $extension = 'bin';
        }

        return blank($this->namePrefix)
            ? "{$this->uid}.{$extension}"
            : "{$this->namePrefix}_{$this->uid}.{$extension}";
    }

    public function getDirectory(): string
    {
        $md5 = md5($this->getName());

        return trim("{$this->directory}/{$md5[0]}{$md5[1]}/{$md5[2]}{$md5[3]}", '/');
    }

    public function withNamePrefix(string $prefix): self
    {
        $this->namePrefix = $prefix;

        return $this;
    }

    public function inDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    public static function fake(string $sourceName = 'test1.png'): self
    {
        return new self(UploadedFile::fake()->image($sourceName));
    }
}
