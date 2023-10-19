<?php

namespace App\Domain\Documents\Models\Factories;

use App\Domain\Documents\Models\Document;
use App\Domain\Documents\Models\DocumentNomenclature;
use App\Domain\Nomenclatures\Models\Nomenclature;
use Ensi\LaravelTestFactories\BaseApiFactory;
use Ensi\LaravelTestFactories\BaseModelFactory;

class DocumentNomenclatureFactory extends BaseModelFactory
{
    protected $model = DocumentNomenclature::class;
    public function definition()
    {
        return [
//            'document_id' => Document::factory()->create()->id,
//            'nomenclature_id' => Nomenclature::factory()->create()->id,
            'quantity' => $this->faker->randomNumber(4),
            'cost_price' => $this->faker->randomNumber(3),
            'base_price' => $this->faker->randomNumber(3),
            'discount' => $this->faker->randomNumber(2),
            'overheads' => $this->faker->randomNumber(2),
            'quantity_fix' => $this->faker->nullable()->randomNumber(2),
            'quantity_no_fix' => $this->faker->nullable()->randomNumber(2),
        ];
    }
}