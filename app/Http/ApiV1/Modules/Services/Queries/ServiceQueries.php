<?php

namespace App\Http\ApiV1\Modules\Services\Queries;

use App\Domain\Services\Models\Service;
use Ensi\QueryBuilderHelpers\Filters\DateFilter;
use Ensi\QueryBuilderHelpers\Filters\NumericFilter;
use Ensi\QueryBuilderHelpers\Filters\StringFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceQueries extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Service::query());

        $this->allowedIncludes(['service_group']);

        $this->allowedSorts([
            'id',
            'created_at',
            'updated_at',
            'seller_id',
            'name',
            'base_price',
            'service_group_id'
        ]);

        $this->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('seller_id'),
            AllowedFilter::exact('service_group_id'),
            AllowedFilter::partial('description_like', 'description'),
            ...StringFilter::make('name')->exact()->contain()->startWith()->endWith(),
            ...DateFilter::make('created_at')->gte()->lte(),
            ...DateFilter::make('updated_at')->gte()->lte(),
            ...NumericFilter::make('base_price')->gte()->lte()
        ]);
    }
}