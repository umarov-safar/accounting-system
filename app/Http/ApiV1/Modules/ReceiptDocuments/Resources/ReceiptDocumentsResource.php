<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Resources;

use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin todo
 */
class ReceiptDocumentsResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        // todo
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'seller_id' => $this->seller_id,
            'document_type_id' => $this->document_type_id,
            'status' => $this->status,
            'document_date' => $this->document_date,
            'company_id' => $this->company_id,
            'store_id' => $this->store_id,
            'summa' => $this->summa,
            'discount' => $this->discount,
            'overheads' => $this->overheads,
            'note' => $this->note,
        ];
    }
}