<?php

namespace App\Http\ApiV1\Modules\ServiceGroups\Controllers;

use App\Domain\Nomenclatures\Models\Nomenclature;
use App\Domain\Services\Actions\Groups\CreateServiceGroupAction;
use App\Domain\Services\Actions\Groups\PatchServiceGroupAction;
use App\Domain\Services\Actions\Groups\ReplaceServiceGroupAction;
use App\Domain\Services\Models\ServiceGroup;
use App\Http\ApiV1\Modules\ServiceGroups\Queries\ServiceGroupsQueries;
use App\Http\ApiV1\Modules\ServiceGroups\Queries\ServiceGroupsTreeQueries;
use App\Http\ApiV1\Modules\ServiceGroups\Requests\CreateServiceGroupRequest;
use App\Http\ApiV1\Modules\ServiceGroups\Requests\PatchServiceGroupRequest;
use App\Http\ApiV1\Modules\ServiceGroups\Requests\ReplaceServiceGroupRequest;
use App\Http\ApiV1\Modules\ServiceGroups\Resources\ServiceGroupsResource;
use App\Http\ApiV1\Modules\ServiceGroups\Resources\ServiceGroupsTreeResource;
use App\Http\ApiV1\Support\Pagination\PageBuilderFactory;
use App\Http\ApiV1\Support\Requests\MassDeleteRequest;
use App\Http\ApiV1\Support\Resources\EmptyResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceGroupsController
{
    public function create(CreateServiceGroupRequest $request, CreateServiceGroupAction $action): ServiceGroupsResource
    {
        return new ServiceGroupsResource($action->execute($request->validated()));
    }

    public function get(int $id, ServiceGroupsQueries $queries): Responsable
    {
        return new ServiceGroupsResource($queries->findOrFail($id));
    }

    public function replace(int $id, ReplaceServiceGroupRequest $request, ReplaceServiceGroupAction $action): Responsable
    {
        return new ServiceGroupsResource($action->execute($id,  $request->validated()));
    }

    public function delete(int $id, ServiceGroupsQueries $queries): Responsable
    {
        $queries->find($id)?->canDelete()->delete();
        return new EmptyResource();
    }

    public function patch(int $id, PatchServiceGroupRequest $request, PatchServiceGroupAction $action): Responsable
    {
        return new ServiceGroupsResource($action->execute($id,  $request->validated()));
    }

    public function massDelete(MassDeleteRequest $request, ServiceGroupsQueries $queries): Responsable
    {
        $queries->whereIn('id', $request->getIds())?->each(function (ServiceGroup $serviceGroup) {
            $serviceGroup->canDelete()->delete();
        });

        return new EmptyResource();
    }

    public function search(PageBuilderFactory $builderFactory, ServiceGroupsQueries $queries): AnonymousResourceCollection
    {
        return ServiceGroupsResource::collectPage($builderFactory->fromQuery($queries)->build());
    }

    public function getTree(ServiceGroupsTreeQueries $queries): ServiceGroupsTreeResource
    {
        return (new ServiceGroupsTreeResource($queries->get()));
    }
}
