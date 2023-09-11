<?php

namespace App\Http\ApiV1\Modules\Accounts\Requests;

use App\Http\ApiV1\OpenApiGenerated\Enums\AccountTypeEnum;
use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class PatchAccountRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['sometimes', 'nullable', 'string'],
            'type' => ['sometimes', 'integer', Rule::enum(AccountTypeEnum::class)],
            'is_active' => ['sometimes', 'boolean'],
            'name' => ['sometimes', 'string'],
            'seller_id' => ['sometimes', 'integer'],
        ];
    }
}
