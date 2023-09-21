<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Controllers;

use App\Domain\Documents\Models\Actions\Receipts\CreateReceiptDocumentAction;
use App\Domain\Documents\Models\Actions\Receipts\ReceiptDocumentSetStatusToCancelAction;
use App\Domain\Documents\Models\Actions\Receipts\ReceiptDocumentSetStatusToDraftAction;
use App\Domain\Documents\Models\Actions\Receipts\ReceiptDocumentSetStatusToFixAction;
use App\Domain\Documents\Models\Actions\Receipts\UpdateReceiptDocumentAction;
use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\Modules\ReceiptDocuments\Queries\ReceiptDocumentQueries;
use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\CreateReceiptDocumentRequest;
use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\PatchReceiptDocumentRequest;
use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\ReplaceReceiptDocumentRequest;
use App\Http\ApiV1\Modules\ReceiptDocuments\Resources\ReceiptDocumentsResource;
use App\Http\ApiV1\Support\Pagination\PageBuilderFactory;
use App\Http\ApiV1\Support\Resources\EmptyResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class ReceiptDocumentsController
{
    public function create(CreateReceiptDocumentRequest $request, CreateReceiptDocumentAction $action): Responsable
    {
        return new ReceiptDocumentsResource($action->execute($request->validated()));
    }

    public function get(int $id, ReceiptDocumentQueries $queries): Responsable
    {
        return new ReceiptDocumentsResource($queries->findOrFail($id));
    }

    public function replace(int $id, ReplaceReceiptDocumentRequest $request, UpdateReceiptDocumentAction $action): Responsable
    {
        return new ReceiptDocumentsResource($action->execute($id, $request->validated()));
    }

    public function delete(int $id, ReceiptDocumentQueries $queries): Responsable
    {
        $queries->find($id)?->canBeDelete()->delete();

        return new EmptyResource();
    }

    public function patch(int $id, PatchReceiptDocumentRequest $request, UpdateReceiptDocumentAction $action): Responsable
    {
        return new ReceiptDocumentsResource($action->execute($id, $request->validated()));
    }

    public function setStatusToFix(int $id, ReceiptDocumentSetStatusToFixAction $action): Responsable
    {
        $action->execute($id);

        return new EmptyResource();
    }

    public function setStatusToCancel(int $id, ReceiptDocumentSetStatusToCancelAction $action): Responsable
    {

        $action->execute($id);

        return new EmptyResource();
    }

    public function setStatusToDraft(int $id, ReceiptDocumentSetStatusToDraftAction $action): Responsable
    {
        $action->execute($id);

        return new EmptyResource();
    }

    public function search(PageBuilderFactory $builderFactory, ReceiptDocumentQueries $queries): Responsable
    {
        return ReceiptDocumentsResource::collectPage($builderFactory->fromQuery($queries)->build());
    }

    public function searchOne(ReceiptDocumentQueries $queries): Responsable
    {
        $recDoc = $queries->first();

        if ($recDoc) {
            return new ReceiptDocumentsResource($recDoc);
        }

        return new EmptyResource();
    }
}
