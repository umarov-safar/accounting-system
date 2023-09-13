<?php

namespace App\Domain\Services\Actions\Groups;

use App\Domain\Services\Models\ServiceGroup;
use Illuminate\Support\Arr;

class CreateServiceGroupAction
{
    public function execute(array $fields): ServiceGroup
    {
        return ServiceGroup::create(Arr::only($fields, ServiceGroup::FILLABLE));
    }
}