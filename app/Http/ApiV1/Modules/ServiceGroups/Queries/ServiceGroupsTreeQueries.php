<?php

namespace App\Http\ApiV1\Modules\ServiceGroups\Queries;

use App\Domain\Services\Models\ServiceGroup;
use Ensi\QueryBuilderHelpers\Filters\DateFilter;
use Ensi\QueryBuilderHelpers\Filters\StringFilter;
use Illuminate\Http\Request;
use Kalnoy\Nestedset\QueryBuilder as NestedSetQueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceGroupsTreeQueries extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(ServiceGroup::query()->select(['id', 'seller_id',  'parent_id','name', 'description']));

        $this->allowedSorts([
            'id',
            'seller_id',
            'name',
            'parent_id',
            'sort'
        ]);

        $this->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::callback(
                'root_id',
                fn (NestedSetQueryBuilder $query, mixed $value) => $query->whereDescendantOrSelf($value)
            ),
            AllowedFilter::exact('seller_id'),
            AllowedFilter::exact('parent_id'),
            ...StringFilter::make('name')->exact()->contain()->startWith()->endWith(),
            ...DateFilter::make('created_at')->gte()->lte(),
            ...DateFilter::make('updated_at')->gte()->lte()
        ]);
    }
}