<?php

namespace App\Domain\Documents\Models\Scopes;

use App\Domain\Documents\Models\DocumentTypeScope;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentFinanceTypeIdEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ReceiptDocumentTypeScope extends DocumentTypeScope
{
    protected DocumentStoreTypeIdEnum|DocumentFinanceTypeIdEnum $documentTypeId = DocumentStoreTypeIdEnum::RECEIPT;
}