<?php

namespace App\Http\ApiV1\Modules\Accounts\Controllers;

use App\Domain\Accounts\Actions\CreateAccountAction;
use App\Domain\Accounts\Actions\UpdateAccountAction;
use App\Http\ApiV1\Modules\Accounts\Queries\AccountQueries;
use App\Http\ApiV1\Modules\Accounts\Requests\CreateAccountRequest;
use App\Http\ApiV1\Modules\Accounts\Requests\PatchAccountRequest;
use App\Http\ApiV1\Modules\Accounts\Requests\ReplaceAccountRequest;
use App\Http\ApiV1\Modules\Accounts\Resources\AccountsResource;
use App\Http\ApiV1\Support\Pagination\PageBuilderFactory;
use App\Http\ApiV1\Support\Requests\MassDeleteRequest;
use App\Http\ApiV1\Support\Resources\EmptyResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountsController
{
    public function create(CreateAccountRequest $request, CreateAccountAction $action): AccountsResource
    {
        return new AccountsResource($action->execute($request->validated()));
    }

    public function get(int $id, AccountQueries $queries): AccountsResource
    {
        return new AccountsResource($queries->findOrFail($id));
    }

    public function replace(int $id, ReplaceAccountRequest $request, UpdateAccountAction $action): AccountsResource
    {
        return new AccountsResource($action->execute($id, $request->validated()));
    }

    public function delete(int $id, AccountQueries $queries): Responsable
    {
        $queries->find($id)?->canDelete()->delete();
        return new EmptyResource();
    }

    public function patch(int $id, PatchAccountRequest $request, UpdateAccountAction $action): AccountsResource
    {
        return new AccountsResource($action->execute($id, $request->validated()));
    }

    public function massDelete(MassDeleteRequest $request, AccountQueries $queries): EmptyResource
    {
        $queries->whereIn('id', $request->getIds())?->delete();
        return new EmptyResource();
    }

    public function search(PageBuilderFactory $builderFactory, AccountQueries $queries): AnonymousResourceCollection
    {
        return AccountsResource::collectPage($builderFactory->fromQuery($queries)->build());
    }
}
