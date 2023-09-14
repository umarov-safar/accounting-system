<?php

namespace App\Domain\Services\Models\Factories;

use App\Domain\Services\Models\ServiceGroup;
use Ensi\LaravelTestFactories\BaseModelFactory;

class ServiceGroupFactory extends BaseModelFactory
{
    protected $model = ServiceGroup::class;

    public function definition()
    {
        return [
            'seller_id' => $this->faker->randomNumber(2, true),
            'name' => $this->faker->sentence(2),
            'sort' => $this->faker->randomNumber(),
//            'parent_id' => $this->faker->boolean ? ServiceGroup::factory()->create()->id : null,
            'description' => $this->faker->text
        ];
    }
}