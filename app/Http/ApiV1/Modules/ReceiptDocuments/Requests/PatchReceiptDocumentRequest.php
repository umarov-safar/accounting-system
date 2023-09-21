<?php

namespace App\Http\ApiV1\Modules\ReceiptDocuments\Requests;

use App\Domain\Documents\Models\ReceiptDocument;
use App\Http\ApiV1\OpenApiGenerated\Enums\DocumentStatusEnum;
use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class PatchReceiptDocumentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'note' => ['sometimes', 'nullable', 'string'],
            'overheads' => ['sometimes', 'nullable', 'integer'],
            'discount' => ['sometimes', 'nullable', 'integer'],
            'summa' => ['sometimes', 'nullable', 'integer'],
            'store_id' => ['sometimes', 'nullable', 'integer'],
            'company_id' => ['sometimes', 'nullable', 'integer'],
            'document_date' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'integer', Rule::enum(DocumentStatusEnum::class)],
//            'document_type_id' => ['required',  'integer', Rule::enum(DocumentStoreTypeIdEnum::class)],
            'seller_id' => ['sometimes',  'integer'],
            'company_to_id' => ['sometimes', 'nullable', 'integer'],
            'parent_id' => ['sometimes', 'nullable', 'integer', Rule::exists(ReceiptDocument::tableName(), 'id')]
        ];
    }
}
