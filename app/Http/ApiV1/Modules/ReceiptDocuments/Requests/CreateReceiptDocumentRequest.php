<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Requests;

use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStoreTypeIdEnum;
use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

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
            'status' => ['required', 'integer', Rule::enum(DocumentStatusEnum::class)],
            'document_type_id' => ['required',  'integer', Rule::enum(DocumentStoreTypeIdEnum::class)],
            'seller_id' => ['required', 'integer'],
            'company_to_id' => ['nullable', 'integer'],
            'parent_id' => ['nullable', 'integer', Rule::exists(ReceiptDocument::tableName(), 'id')]
        ];
    }
}
