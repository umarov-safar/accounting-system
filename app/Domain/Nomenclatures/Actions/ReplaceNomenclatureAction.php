<?php

namespace App\Domain\Nomenclatures\Actions;

use App\Domain\Nomenclatures\Models\Nomenclature;
use Illuminate\Support\Arr;

class ReplaceNomenclatureAction
{
    public function execute(int $id, array $fields)
    {
        $nomenclature = Nomenclature::findOrFail($id);
        $nomenclature->update(Arr::only($fields, Nomenclature::FILLLABLE));

        return $nomenclature;
    }
}