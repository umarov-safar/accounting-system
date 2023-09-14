<?php

namespace App\Http\ApiV1\Modules\ServiceGroups\Queries;

use App\Domain\Services\Models\ServiceGroup;
use Ensi\QueryBuilderHelpers\Filters\DateFilter;
use Ensi\QueryBuilderHelpers\Filters\StringFilter;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceGroupsQueries extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(ServiceGroup::query());

        $this->allowedIncludes(['services', 'children']);

        $this->allowedSorts([
            'id',
            'created_at',
            'updated_at',
            'seller_id',
            'name',
            'parent_id',
            'sort'
        ]);

        $this->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('seller_id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::partial('description_like', 'description'),
            ...StringFilter::make('name')->exact()->contain()->startWith()->endWith(),
            ...DateFilter::make('created_at')->gte()->lte(),
            ...DateFilter::make('updated_at')->gte()->lte()
        ]);
    }
}