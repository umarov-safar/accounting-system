<?php

namespace App\Domain\Documents\Models\Actions\Receipts;

use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;

class ReceiptDocumentSetStatusToFixAction
{
    public function execute(int $id)
    {
        $document = ReceiptDocument::findOrFail($id);

        return $document->canBeEditWithException()->update(['status' => DocumentStatusEnum::FIX]);
    }
}