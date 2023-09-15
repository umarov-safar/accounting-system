<?php

namespace App\Http\ApiV1\Modules\Services\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class PatchServiceRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['sometimes', 'nullable', 'string'],
            'base_price' => ['sometimes', 'integer'],
            'service_group_id' => ['sometimes', 'integer', Rule::exists('service_groups', 'id')],
            'name' => ['sometimes', 'string'],
            'seller_id' => ['sometimes', 'integer'],
        ];
    }
}
