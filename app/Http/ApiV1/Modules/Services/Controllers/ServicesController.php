<?php

namespace App\Http\ApiV1\Modules\Services\Controllers;

use App\Http\ApiV1\Modules\Services\Requests\CreateServiceRequest;
use App\Http\ApiV1\Modules\Services\Requests\PatchServiceRequest;
use App\Http\ApiV1\Modules\Services\Requests\ReplaceServiceRequest;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class ServicesController
{
    public function create(CreateServiceRequest $request): Responsable
    {
        //
    }

    public function get(int $id): Responsable
    {
        //
    }

    public function replace(int $id, ReplaceServiceRequest $request): Responsable
    {
        //
    }

    public function delete(int $id, Request $request): Responsable
    {
        //
    }

    public function patch(int $id, PatchServiceRequest $request): Responsable
    {
        //
    }

    public function massDelete(Request $request): Responsable
    {
        //
    }

    public function search(Request $request): Responsable
    {
        //
    }

    public function searchOne(Request $request): Responsable
    {
        //
    }
}
