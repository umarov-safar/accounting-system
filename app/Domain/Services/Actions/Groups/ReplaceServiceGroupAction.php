<?php

namespace App\Domain\Services\Actions\Groups;

use App\Domain\Services\Models\ServiceGroup;

class ReplaceServiceGroupAction
{
    public function execute(int $id, array $fields): ServiceGroup
    {
        $servGroup = ServiceGroup::findOrFail($id);
        $servGroup->fill($fields)->save();
        return $servGroup;
    }
}