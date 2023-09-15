<?php

namespace App\Http\ApiV1\Modules\Services\Controllers;

use App\Domain\Services\Actions\CreateServiceAction;
use App\Domain\Services\Actions\PatchServiceAction;
use App\Domain\Services\Actions\ReplaceServiceAction;
use App\Domain\Services\Models\Service;
use App\Http\ApiV1\Modules\Services\Queries\ServiceQueries;
use App\Http\ApiV1\Modules\Services\Requests\CreateServiceRequest;
use App\Http\ApiV1\Modules\Services\Requests\PatchServiceRequest;
use App\Http\ApiV1\Modules\Services\Requests\ReplaceServiceRequest;
use App\Http\ApiV1\Modules\Services\Resources\ServicesResource;
use App\Http\ApiV1\Support\Pagination\PageBuilderFactory;
use App\Http\ApiV1\Support\Requests\MassDeleteRequest;
use App\Http\ApiV1\Support\Resources\EmptyResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServicesController
{
    public function create(CreateServiceRequest $request, CreateServiceAction $action): Responsable
    {
        return new ServicesResource($action->execute($request->validated()));
    }

    public function get(int $id, ServiceQueries $queries): Responsable
    {
        return new ServicesResource($queries->findOrFail($id));
    }

    public function replace(int $id, ReplaceServiceRequest $request, ReplaceServiceAction $action): Responsable
    {
        return new ServicesResource($action->execute($id, $request->validated()));
    }

    public function delete(int $id, ServiceQueries $queries): Responsable
    {
        $queries->find($id)?->canDelete()->delete();
        return new EmptyResource();
    }

    public function patch(int $id, PatchServiceRequest $request, PatchServiceAction $action): Responsable
    {
        return new ServicesResource($action->execute($id, $request->validated()));
    }

    public function massDelete(MassDeleteRequest $request, ServiceQueries $queries): Responsable
    {
        $queries->whereIn('id', $request->getIds())?->each(function (Service $service) {
            $service->canDelete()->delete();
        });

        return new EmptyResource();
    }

    public function search(
        PageBuilderFactory $builderFactory, ServiceQueries $queries
    ): AnonymousResourceCollection
    {
        return ServicesResource::collectPage($builderFactory->fromQuery($queries)->build());
    }

    public function searchOne(ServiceQueries $queries): Responsable
    {
        $service = $queries->first();
        if (!$service) {
            return new EmptyResource();
        }
        return new ServicesResource($service);
    }
}
