<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Tests\Factories;

use Ensi\LaravelTestFactories\BaseApiFactory;

class ReceiptDocumentsFactory extends BaseApiFactory
{

    protected function definition(): array
    {
        return [
            'seller_id' => $this->faker->randomNumber(2),
            'document_type_id' => $this->faker->randomNumber(2),
            'status' => $this->faker->randomElement([1,2,3]),
            'document_date' => $this->faker->date,
            'company_id' => $this->faker->randomNumber(2),
            'store_id' => $this->faker->randomNumber(2),
            'summa' => $this->faker->numerify('####'),
            'discount' => $this->faker->randomNumber(2),
            'overheads' => $this->faker->numerify("####"),
            'note' => $this->faker->text,
        ];
    }

    public function make(array $extra = [])
    {
        // TODO: Implement make() method.
    }
}