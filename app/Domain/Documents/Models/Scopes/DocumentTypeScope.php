<?php

namespace App\Domain\Documents\Models\Scopes;

use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentFinanceTypeIdEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DocumentTypeScope implements Scope
{
    public function __construct(
        protected DocumentStoreTypeIdEnum|DocumentFinanceTypeIdEnum $documentTypeId
    )
    {
    }

    public function apply(Builder $builder, Model $model)
    {
       $builder->where('document_type_id', $this->documentTypeId->value);
    }
}