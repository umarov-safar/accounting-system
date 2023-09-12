<?php

namespace App\Http\ApiV1\Modules\Nomenclatures\Queries;

use App\Domain\Nomenclatures\Models\Nomenclature;
use Ensi\QueryBuilderHelpers\Filters\DateFilter;
use Ensi\QueryBuilderHelpers\Filters\NumericFilter;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NomenclatureQueries extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Nomenclature::query());

        $this->allowedSorts([
            'seller_id',
            'obj_type',
            'obj_id',
            'is_service',
            'base_price',
            'created_at',
            'updated_at',
        ]);

        $this->allowedFilters([
            AllowedFilter::exact('seller_id'),
            AllowedFilter::exact('obj_type'),
            AllowedFilter::exact('obj_id'),
            AllowedFilter::exact('is_service'),
            ...NumericFilter::make('base_price')->exact()->gte()->lte(),
            ...DateFilter::make('created_at')->exact()->gte()->lte(),
            ...DateFilter::make('updated_at')->exact()->gte()->lte(),
        ]);
    }

}