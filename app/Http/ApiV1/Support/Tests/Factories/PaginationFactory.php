<?php

namespace App\Http\ApiV1\Support\Tests\Factories;

use App\Http\ApiV1\OpenApiGenerated\Enums\PaginationTypeEnum;

class PaginationFactory extends BaseApiFactory
{
    protected function definition(): array
    {
        $limit = $this->faker->numberBetween(1, 200);
        $offset = $this->faker->numberBetween(0, 200);

        return [
            'type' => $this->faker->randomElement(PaginationTypeEnum::cases()),
            'cursor' => $this->faker->bothify('******************************'),
            'next_cursor' => $this->faker->bothify('******************************'),
            'previous_cursor' => $this->faker->bothify('******************************'),
            'limit' => $limit,
            'offset' => $offset,
            'total' => $this->faker->numberBetween($limit + $offset, $limit + $offset + 200),
        ];
    }

    public function make(array $extra = []): array
    {
        return $this->makeArray($extra);
    }

    public function makeResponseOffset(string $className, array $extra = [])
    {
        return new $className(
            $this->only(['type', 'limit', 'total', 'offset'])->make(array_merge($extra, ['type' => PaginationTypeEnum::OFFSET->value])),
        );
    }

    public function makeRequestOffset(array $extra = []): array
    {
        return $this->only(['type', 'limit', 'offset'])->make(array_merge($extra, ['type' => PaginationTypeEnum::OFFSET->value]));
    }

    public function makeResponseCursor(string $className, array $extra = [])
    {
        return new $className(
            $this->only(['type', 'limit', 'total', 'cursor', 'next_cursor', 'previous_cursor'])
                ->make(array_merge($extra, ['type' => PaginationTypeEnum::CURSOR->value])),
        );
    }

    public function makeRequestCursor(array $extra = []): array
    {
        return $this->only(['type', 'limit', 'cursor', 'next_cursor', 'previous_cursor'])
            ->make(array_merge($extra, ['type' => PaginationTypeEnum::CURSOR->value]));
    }
}
