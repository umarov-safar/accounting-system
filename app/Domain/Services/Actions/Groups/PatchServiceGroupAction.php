<?php

namespace App\Domain\Services\Actions\Groups;

use App\Domain\Services\Models\ServiceGroup;

class PatchServiceGroupAction
{
    public function execute(int $id, array $fields)
    {
        $servGroup = ServiceGroup::findOrFail($id);
        $servGroup->fill($fields)->save();
        return $servGroup;
    }
}