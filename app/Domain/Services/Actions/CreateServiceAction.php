<?php

namespace App\Domain\Services\Actions;

use App\Domain\Services\Models\Service;
use Illuminate\Support\Arr;

class CreateServiceAction
{
    public function execute(array $fields): Service
    {
        return Service::create(Arr::only($fields, Service::FILLABLE));
    }
}