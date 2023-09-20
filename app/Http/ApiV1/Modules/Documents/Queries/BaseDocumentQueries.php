<?php

namespace App\Http\ApiV1\Modules\Documents\Queries;

use App\Domain\Documents\Models\Document;
use Ensi\QueryBuilderHelpers\Filters\DateFilter;
use Ensi\QueryBuilderHelpers\Filters\NumericFilter;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseDocumentQueries extends QueryBuilder
{
    public function __construct(Document $subject, ?Request $request = null)
    {
        parent::__construct($subject::query(), $request);

        $this->allowedSorts([
            'seller_id',
            'document_type_id',
            'status',
            'document_date',
            'company_id',
            'store_id',
            'summa',
            'discount',
            'overheads',
            'note',
            'payment_end_date',
            'parent_id'
        ]);

        $this->allowedFilters([
                AllowedFilter::exact('seller_id'),
                AllowedFilter::exact('document_type_id'),
                AllowedFilter::exact('status'),
                ...DateFilter::make('document_date')->gte()->lte()->exact(),
                ...DateFilter::make('created_at')->gte()->lte()->exact(),
                ...DateFilter::make('updated_at')->gte()->lte()->exact(),
                AllowedFilter::exact('company_id'),
//                'contractor_id',
                AllowedFilter::exact('store_id'),
//                'store_to_id',
                ...NumericFilter::make('summa')->gte()->lte()->exact(),
                ...NumericFilter::make('discount')->exact()->gte()->lte(),
                ...NumericFilter::make('overheads')->exact()->gte()->lte(),
//                'payment_end_date',
                AllowedFilter::exact('company_to_id'),
                AllowedFilter::exact('parent_id'),

        ]);
    }
}
