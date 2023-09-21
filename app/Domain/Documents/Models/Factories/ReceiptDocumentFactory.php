<?php

namespace App\Domain\Documents\Models\Factories;

use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Arr;
use Illuminate\Database\Eloquent\Model;

class ReceiptDocumentFactory extends BaseDocumentFactory
{
    public function definition()
    {
        // parent definition except some not necessary field
        $baseDefinition =  Arr::except(parent::definition(), ['contractor_id', 'store_to_id', 'payment_end_date']);

        return array_merge($baseDefinition, ['document_type_id' => DocumentStoreTypeIdEnum::RECEIPT->value]);
    }
}
