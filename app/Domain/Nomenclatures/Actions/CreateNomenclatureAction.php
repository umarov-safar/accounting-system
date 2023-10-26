<?php

namespace App\Domain\Nomenclatures\Actions;

use App\Domain\Nomenclatures\Models\Nomenclature;
use Eijen\Zstore\Api\NomenclaturesApi;
use Eijen\Zstore\Model\CreateNomenclatureRequest;
use Illuminate\Support\Arr;

class CreateNomenclatureAction
{
    public function __construct(
        protected  NomenclaturesApi $nomenclaturesApi
    )
    {
    }

    public function execute(array $fields)
    {
        $request = new CreateNomenclatureRequest(['itemname' => $fields['seller_id']]);


        $response = $this->nomenclaturesApi->createNomenclature($request);
        $fields['zippy_nomenclature_id'] = $response->getData()->getItemId();

        return Nomenclature::create(Arr::only($fields, Nomenclature::FILLLABLE));
    }
}