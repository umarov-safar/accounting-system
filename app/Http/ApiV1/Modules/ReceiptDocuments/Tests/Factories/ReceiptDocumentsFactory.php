<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Tests\Factories;

use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Ensi\LaravelTestFactories\BaseApiFactory;
use Illuminate\Support\Arr;

class ReceiptDocumentsFactory extends BaseDocumentFactory
{

    protected function definition(): array
    {
        // parent definition except some not necessary field
        $baseDefinition =  Arr::except(parent::definition(), ['contractor_id', 'store_to_id', 'payment_end_date']);

        return array_merge($baseDefinition, ['document_type_id' => DocumentStoreTypeIdEnum::RECEIPT->value]);
    }
}