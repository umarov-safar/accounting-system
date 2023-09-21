<?php

namespace App\Domain\Documents\Models\Actions\Receipts;

use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;

class ReceiptDocumentSetStatusToDraftAction
{
    public function execute(int $id)
    {
        $document = ReceiptDocument::findOrFail($id);

        return $document->update(['status' => DocumentStatusEnum::DRAFT]);
    }

}