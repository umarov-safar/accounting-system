<?php

namespace App\Domain\Documents\Models\Factories;

use App\Domain\Documents\Models\Document;
use Ensi\LaravelTestFactories\BaseModelFactory;

class DocumentFactory extends BaseModelFactory
{
    protected $model = Document::class;

    public function definition()
    {
        return [
            'seller_id' => $this->faker->randomNumber(2),
            'document_type_id' => $this->faker->randomNumber(2),
            'status' => $this->faker->randomElement([1,2,3]),
            'document_date' => $this->faker->date,
            'company_id' => $this->faker->randomNumber(2),
            'contractor_id' => $this->faker->randomNumber(2),
            'store_id' => $this->faker->randomNumber(2),
            'store_to_id' => $this->faker->randomNumber(2),
            'summa' => $this->faker->numerify('####'),
            'discount' => $this->faker->randomNumber(2),
            'overheads' => $this->faker->numerify("####"),
            'note' => $this->faker->text,
            'payment_end_date' => $this->faker->date
        ];
    }
}