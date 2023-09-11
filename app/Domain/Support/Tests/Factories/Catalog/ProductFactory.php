<?php

namespace App\Domain\Support\Tests\Factories\Catalog;

use Ensi\LaravelTestFactories\BaseApiFactory;
use Ensi\PimClient\Dto\Product;
use Ensi\PimClient\Dto\ProductStatusEnum;
use Ensi\PimClient\Dto\ProductTypeEnum;
use Ensi\PimClient\Dto\ResponseBodyPagination;
use Ensi\PimClient\Dto\SearchProductsResponse;

class ProductFactory extends BaseApiFactory
{
    protected function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'code' => $this->faker->unique()->slug,
            'description' => $this->faker->text,
            'type' => $this->faker->randomElement(ProductTypeEnum::getAllowableEnumValues()),
            'status_id' => $this->faker->randomElement(ProductStatusEnum::getAllowableEnumValues()),
            'status_comment' => $this->faker->optional()->sentence,
            'allow_publish' => $this->faker->boolean,

            'external_id' => $this->faker->unique()->numerify('######'),
            'barcode' => $this->faker->ean13(),
            'vendor_code' => $this->faker->numerify('###-###-###'),

            'weight' => $this->faker->randomFloat(3, 1, 100),
            'weight_gross' => $this->faker->randomFloat(3, 1, 100),
            'length' => $this->faker->randomFloat(2, 1, 1000),
            'height' => $this->faker->randomFloat(2, 1, 1000),
            'width' => $this->faker->randomFloat(2, 1, 1000),
            'is_adult' => $this->faker->boolean,

            'category_id' => $this->faker->modelId(),
        ];
    }

    public function make(array $extra = []): Product
    {
        return new Product($this->makeArray($extra));
    }

    public function makeResponseSearch(array $extra = [], int $count = 1, ?ResponseBodyPagination $pagination = null): SearchProductsResponse
    {
        return $this->generateResponseSearch(SearchProductsResponse::class, $extra, $count, $pagination);
    }
}
