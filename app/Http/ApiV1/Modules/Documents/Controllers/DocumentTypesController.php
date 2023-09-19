<?php

namespace App\Http\ApiV1\Modules\Documents\Controllers;

use App\Domain\Documents\Models\DocumentType;
use App\Http\ApiV1\Modules\Documents\Resources\DocumentTypesResource;
use Illuminate\Contracts\Support\Responsable;

class DocumentTypesController
{
    public function storeTypeDocs(): Responsable
    {
        return DocumentTypesResource::collection(DocumentType::store());
    }

    public function financeTypeDocs(): Responsable
    {
        return DocumentTypesResource::collection(DocumentType::store());
    }
}
