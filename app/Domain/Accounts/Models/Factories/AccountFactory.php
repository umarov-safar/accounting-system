<?php

namespace App\Domain\Accounts\Models\Factories;

use App\Domain\Accounts\Models\Account;
use App\Http\ApiV1\OpenApiGenerated\Enums\AccountTypeEnum;
use Ensi\LaravelTestFactories\BaseApiFactory;
use Ensi\LaravelTestFactories\BaseModelFactory;

class AccountFactory extends BaseModelFactory
{
    protected $model = Account::class;

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
}