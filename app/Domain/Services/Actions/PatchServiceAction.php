<?php

namespace App\Domain\Services\Actions;

use App\Domain\Services\Models\Service;

class PatchServiceAction
{
    public function execute(int $id, array $fields)
    {
        $service = Service::findOrFail($id);
        $service->fill($fields)->save();
        return $service;
    }
}