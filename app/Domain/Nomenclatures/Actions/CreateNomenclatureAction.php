<?php

namespace App\Domain\Nomenclatures\Actions;

use App\Domain\Nomenclatures\Models\Nomenclature;
use Illuminate\Support\Arr;

class CreateNomenclatureAction
{
    public function execute(array $fields)
    {
        return Nomenclature::create(Arr::only($fields, Nomenclature::FILLLABLE));
    }
}