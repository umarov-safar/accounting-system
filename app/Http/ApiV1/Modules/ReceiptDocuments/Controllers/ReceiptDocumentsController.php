<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Controllers;

use App\Domain\Documents\Models\Actions\Receipts\CreateReceiptDocumentAction;
use App\Domain\Documents\Models\Actions\Receipts\UpdateReceiptDocumentAction;
use App\Http\ApiV1\Modules\ReceiptDocuments\Queries\ReceiptDocumentQueries;
use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\CreateReceiptDocumentRequest;
use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\PatchReceiptDocumentRequest;
use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\ReplaceReceiptDocumentRequest;
use App\Http\ApiV1\Modules\ReceiptDocuments\Resources\ReceiptDocumentsResource;
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

    public function delete(int $id, Request $request): Responsable
    {
        //
    }

    public function patch(int $id, PatchReceiptDocumentRequest $request): Responsable
    {
        //
    }

    public function setStatusToFix(int $id, Request $request): Responsable
    {
        //
    }

    public function setStatusToCancel(int $id, Request $request): Responsable
    {
        //
    }

    public function setStatusToDraft(int $id, Request $request): Responsable
    {
        //
    }

    public function search(Request $request): Responsable
    {
        //
    }

    public function searchOne(Request $request): Responsable
    {
        //
    }
}
