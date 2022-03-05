<?php

namespace App\Http\ApiV1\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class FoosController
{
    public function get()
    {
        throw new ModelNotFoundException('Foo');
    }
}
