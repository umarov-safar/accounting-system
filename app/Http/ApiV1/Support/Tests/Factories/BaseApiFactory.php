<?php

namespace App\Http\ApiV1\Support\Tests\Factories;

use Ensi\TestFactories\Factory;

abstract class BaseApiFactory extends Factory
{
    public ?int $id = null;

    public function withId(?int $id = null): static
    {
        return $this->immutableSet('id', $id ?? $this->faker->randomNumber());
    }

    protected function optionalId()
    {
        return $this->whenNotNull($this->id, $this->id);
    }
}
