<?php

namespace App\Domain\Documents\Models\Actions\Receipts;


use _PHPStan_53d0d2174\Symfony\Component\Finder\Exception\AccessDeniedException;
use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Illuminate\Support\Arr;

class UpdateReceiptDocumentAction
{
    public function execute(int $id, array $fields): ReceiptDocument
    {
        $document = ReceiptDocument::findOrFail($id);

        throw_unless(
            $document->canBeEdit(),
            new AccessDeniedException('Статус должен быть черновиком')
        );

        $document->fill($fields)->save();

        return $document;
    }
}
