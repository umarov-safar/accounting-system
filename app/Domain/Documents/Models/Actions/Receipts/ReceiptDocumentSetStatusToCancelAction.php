<?php

namespace App\Domain\Documents\Models\Actions\Receipts;

use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;

class ReceiptDocumentSetStatusToCancelAction
{
    public function execute(int $id)
    {
        $document = ReceiptDocument::findOrFail($id);

        return $document->canBeSetStatusToCancelWithException()->update(['status' => DocumentStatusEnum::CANCEL]);
    }

}