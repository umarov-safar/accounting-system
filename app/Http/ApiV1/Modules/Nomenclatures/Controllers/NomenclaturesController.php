<?php

namespace App\Http\ApiV1\Modules\Nomenclatures\Controllers;

use App\Domain\Nomenclatures\Actions\CreateNomenclatureAction;
use App\Domain\Nomenclatures\Actions\PatchNomenclatureAction;
use App\Domain\Nomenclatures\Actions\ReplaceNomenclatureAction;
use App\Domain\Nomenclatures\Models\Nomenclature;
use App\Http\ApiV1\Modules\Nomenclatures\Queries\NomenclatureQueries;
use App\Http\ApiV1\Modules\Nomenclatures\Requests\CreateNomenclatureRequest;
use App\Http\ApiV1\Modules\Nomenclatures\Requests\PatchNomenclatureRequest;
use App\Http\ApiV1\Modules\Nomenclatures\Requests\ReplaceNomenclatureRequest;
use App\Http\ApiV1\Modules\Nomenclatures\Resources\NomenclaturesResource;
use App\Http\ApiV1\Support\Pagination\PageBuilderFactory;
use App\Http\ApiV1\Support\Requests\MassDeleteRequest;
use App\Http\ApiV1\Support\Resources\EmptyResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NomenclaturesController
{
    public function create(CreateNomenclatureRequest $request, CreateNomenclatureAction $action): NomenclaturesResource
    {
        return new NomenclaturesResource($action->execute($request->validated()));
    }

    public function get(int $id, NomenclatureQueries $queries): NomenclaturesResource
    {
        return new NomenclaturesResource($queries->findOrFail($id));
    }

    public function replace(int $id, ReplaceNomenclatureRequest $request, ReplaceNomenclatureAction $action): NomenclaturesResource
    {
        return new NomenclaturesResource($action->execute($id, $request->validated()));
    }

    public function delete(int $id, NomenclatureQueries $queries): EmptyResource
    {
        $queries->find($id)?->canDelete()->delete();
        return new EmptyResource();
    }

    public function patch(int $id, PatchNomenclatureRequest $request, PatchNomenclatureAction $action): Responsable
    {
        return new NomenclaturesResource($action->execute($id, $request->validated()));
    }

    public function massDelete(MassDeleteRequest $request, NomenclatureQueries $queries): Responsable
    {
        $queries->whereIn('id', $request->getIds())?->each(function (Nomenclature $nomenclature) {
            $nomenclature->canDelete()->delete();
        });
        return new EmptyResource();
    }

    public function search(PageBuilderFactory $builderFactory, NomenclatureQueries $queries): AnonymousResourceCollection
    {
        return NomenclaturesResource::collectPage($builderFactory->fromQuery($queries)->build());
    }

    public function searchOne(NomenclatureQueries $queries): Responsable
    {
        $nom = $queries->first();

        if ($nom) {
            return new NomenclaturesResource($nom);
        }

        return new EmptyResource();
    }
}
