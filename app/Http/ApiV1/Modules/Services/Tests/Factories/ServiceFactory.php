<?php

namespace App\Http\ApiV1\Modules\Services\Tests\Factories;

use App\Domain\Services\Models\ServiceGroup;
use Ensi\LaravelTestFactories\BaseApiFactory;

class ServiceFactory extends BaseApiFactory
{
    protected function definition(): array
    {
        return [
            'seller_id' => $this->faker->randomNumber(2, true),
            'name' => $this->faker->sentence(2),
            'base_price' => $this->faker->randomNumber(4),
            'service_group_id' => ServiceGroup::factory()->create()->id,
            'description' => $this->faker->text
        ];
    }

    public function make(array $extra = [])
    {
        return $this->makeArray($extra);
    }
}