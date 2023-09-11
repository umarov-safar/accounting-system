<?php

namespace App\Http\ApiV1\Modules\Accounts\Tests\Factories;

use App\Http\ApiV1\OpenApiGenerated\Enums\AccountTypeEnum;
use Ensi\LaravelTestFactories\BaseApiFactory;

class AccountFactory extends BaseApiFactory
{
    public function definition(): array
    {
        return [
            'seller_id' => $this->faker->randomNumber(2, true),
            'name' => $this->faker->sentence(2),
            'is_active' => $this->faker->boolean,
            'type' => $this->faker->randomEnum(AccountTypeEnum::cases()),
            'description' => $this->faker->text
        ];
    }

    public function make(array $extra = [])
    {
        return $this->makeArray($extra);
    }
}