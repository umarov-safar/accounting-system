<?php

namespace App\Http\ApiV1\Modules\Accounts\Requests;

use App\Http\ApiV1\OpenApiGenerated\Enums\AccountTypeEnum;
use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class CreateAccountRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string'],
            'type' => ['required', 'integer', Rule::enum(AccountTypeEnum::class)],
            'is_active' => ['required', 'boolean'],
            'name' => ['required', 'string'],
            'seller_id' => ['required', 'integer'],
        ];
    }
}
