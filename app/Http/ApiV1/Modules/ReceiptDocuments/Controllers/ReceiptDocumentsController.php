<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Controllers;

use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\CreateReceiptDocumentRequest;
use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\PatchReceiptDocumentRequest;
use App\Http\ApiV1\Modules\ReceiptDocuments\Requests\ReplaceReceiptDocumentRequest;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class ReceiptDocumentsController
{
    public function create(CreateReceiptDocumentRequest $request): Responsable
    {
        //
    }

    public function get(int $id): Responsable
    {
        //
    }

    public function replace(int $id, ReplaceReceiptDocumentRequest $request): Responsable
    {
        //
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
