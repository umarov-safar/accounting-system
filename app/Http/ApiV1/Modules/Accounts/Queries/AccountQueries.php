<?php

namespace App\Http\ApiV1\Modules\Accounts\Queries;

use App\Domain\Accounts\Models\Account;
use Ensi\QueryBuilderHelpers\Filters\DateFilter;
use Ensi\QueryBuilderHelpers\Filters\StringFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AccountQueries extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Account::query());

        $this->allowedIncludes([]);

        $this->allowedSorts(['name', 'is_active', 'seller_id', 'type', 'created_at', 'id']);

        $this->allowedFilters([
           ...StringFilter::make('name')->contain()->exact()->endWith()->startWith(),
            AllowedFilter::exact('seller_id'),
            AllowedFilter::exact('is_active'),
            AllowedFilter::exact('type'),
            AllowedFilter::partial('description_like'),
            ...DateFilter::make('created_at')->lte()->gte(),
            ...DateFilter::make('updated_at')->lte()->gte()
        ]);
    }
}