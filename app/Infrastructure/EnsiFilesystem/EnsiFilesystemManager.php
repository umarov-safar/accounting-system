<?php

namespace App\Infrastructure\EnsiFilesystem;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

class EnsiFilesystemManager extends FilesystemManager
{
    /**
     * Get protected disk for service or current service.
     */
    public function protected(?string $service = null): Filesystem
    {
        $service = $service ?? config('app.service_code');

        return $this->disk("ensi_{$service}_protected");
    }

    /**
     * Get public disk for service or current service.
     */
    public function public(?string $service = null): Filesystem
    {
        $service = $service ?? config('app.service_code');

        return $this->disk("ensi_{$service}_public");
    }
}
