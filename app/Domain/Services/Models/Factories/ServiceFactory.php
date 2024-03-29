<?php

namespace App\Domain\Services\Models\Factories;

use App\Domain\Services\Models\Service;
use App\Domain\Services\Models\ServiceGroup;
use Ensi\LaravelTestFactories\BaseModelFactory;

class ServiceFactory extends BaseModelFactory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'seller_id' => $this->faker->randomNumber(2, true),
            'name' => $this->faker->sentence(2),
            'base_price' => $this->faker->randomNumber(4),
            'service_group_id' => ServiceGroup::factory()->create()->id,
            'description' => $this->faker->text
        ];
    }
}