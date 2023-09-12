<?php

namespace App\Support\Files;

use Closure;
use Exception;

trait LinksFile
{
    protected FileStorage $storage;

    protected function link(FileIntent $intent, Closure $callback): void
    {
        $filePath = $this->storage->add($intent);

        try {
            $filePathOld = $callback($filePath);
        } catch (Exception $e) {
            $this->removeFile($filePath);

            throw $e;
        }
        $this->removeFile($filePathOld);
    }

    protected function unlink(Closure $callback): void
    {
        $this->removeFile($callback());
    }

    protected function removeFile(?string $reference): void
    {
        if ($reference !== null) {
            $this->storage->remove($reference);
        }
    }
}
