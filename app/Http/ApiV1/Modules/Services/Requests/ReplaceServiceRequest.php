<?php

namespace App\Http\ApiV1\Modules\Services\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class ReplaceServiceRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'integer'],
            'service_group_id' => ['required', 'integer', Rule::exists('service_groups', 'id')],
            'name' => ['required', 'string'],
            'seller_id' => ['required', 'integer'],
        ];
    }
}
