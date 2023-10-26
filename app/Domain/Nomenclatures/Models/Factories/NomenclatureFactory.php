<?php

namespace App\Domain\Nomenclatures\Models\Factories;

use App\Domain\Nomenclatures\Models\Nomenclature;
use App\Http\ApiV1\OpenApiGenerated\Enums\ObjectTypeEnum;
use Ensi\LaravelTestFactories\BaseModelFactory;

class NomenclatureFactory extends BaseModelFactory
{
    protected $model = Nomenclature::class;

    public function definition()
    {
        return [
            'seller_id' => $this->faker->randomNumber(2, true),
            'is_service' => $this->faker->boolean,
            'obj_type' => $this->faker->randomEnum(ObjectTypeEnum::cases()),
            'obj_id' => $this->faker->randomNumber(3, true),
            'base_price' => $this->faker->randomNumber(5),
            'is_new' => $this->faker->boolean(),
            'cardonor_id' => $this->faker->nullable()->randomNumber(2),
            'zippy_nomenclature_id' => $this->faker->randomNumber()
        ];
    }
}