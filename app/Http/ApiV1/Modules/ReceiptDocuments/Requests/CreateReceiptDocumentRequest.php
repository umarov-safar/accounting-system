<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;

class CreateReceiptDocumentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'note' => ['nullable', 'string'],
            'overheads' => ['nullable', 'integer'],
            'discount' => ['nullable', 'integer'],
            'summa' => ['nullable', 'integer'],
            'store_id' => ['nullable', 'integer'],
            'company_id' => ['nullable', 'integer'],
            'document_date' => ['nullable', 'date'],
            'status' => ['required', 'nullable', 'integer'],
            'document_type_id' => ['required', 'nullable', 'integer'],
            'seller_id' => ['required', 'integer'],
        ];
    }
}
