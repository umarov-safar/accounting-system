<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Tests\Factories;

use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Ensi\LaravelTestFactories\BaseApiFactory;

abstract class BaseDocumentFactory extends BaseApiFactory
{

    protected function definition(): array
    {
        return [
            'seller_id' => $this->faker->randomNumber(3),
            'document_type_id' => $this->faker->randomEnum(DocumentStoreTypeIdEnum::cases()),
            'status' => $this->faker->randomEnum(DocumentStatusEnum::cases()),
            'document_date' => $this->faker->date,
            'company_id' => $this->faker->randomNumber(2),
            'company_to_id' => $this->faker->randomNumber(2),
            'store_id' => $this->faker->randomNumber(1),
            'store_to_id' => $this->faker->randomDigitNotNull(),
            'summa' => $this->faker->randomNumber(4),
            'discount' => $this->faker->randomNumber(2),
            'overheads' => $this->faker->randomNumber(4),
            'note' => $this->faker->sentence(4),
            'payment_end_date' => $this->faker->date,
            'parent_id' => ReceiptDocument::factory()->create()->id,
            'contractor_id' => $this->faker->randomDigitNotNull(),
        ];
    }

    public function make(array $extra = [])
    {
        return $this->makeArray($extra);
    }
}