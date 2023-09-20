<?php

namespace App\Domain\Documents\Models\Actions\Receipts;


use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Illuminate\Support\Arr;

class UpdateReceiptDocumentAction
{
    public function execute(int $id, array $fields): ReceiptDocument
    {
        $document = ReceiptDocument::documentTypeReceipt()->findOrFail($id);

        if ($document->status === DocumentStatusEnum::DRAFT) {

        }

    }
}
