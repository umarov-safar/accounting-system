<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Tests\Factories;

use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Ensi\LaravelTestFactories\BaseApiFactory;

class ReceiptDocumentsFactory extends BaseDocumentFactory
{

    protected function definition(): array
    {
        return \Arr::except(parent::definition(), ['contractor_id', 'store_to_id', 'payment_end_date']);
    }
}