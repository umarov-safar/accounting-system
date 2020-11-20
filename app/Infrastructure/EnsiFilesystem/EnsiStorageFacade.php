<?php

namespace App\Infrastructure\EnsiFilesystem;

use Illuminate\Support\Facades\Facade;

class EnsiStorageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ensi.filesystem';
    }
}
