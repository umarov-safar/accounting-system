<?php

namespace App\Http\ApiV1\Modules\ServiceGroups\Tests\Factories;

use App\Domain\Services\Models\ServiceGroup;
use Ensi\LaravelTestFactories\BaseApiFactory;

class ServiceGroupFactory extends BaseApiFactory
{
    public function definition(): array
    {
        return [
            'seller_id' => $this->faker->randomNumber(2, true),
            'name' => $this->faker->word,
            'sort' => $this->faker->randomNumber(),
            'parent_id' => $this->faker->boolean ? ServiceGroup::factory()->create()->id : null,
            'description' => $this->faker->text
        ];
    }

    public function make(array $extra = [])
    {
        return $this->makeArray($extra);
    }

}