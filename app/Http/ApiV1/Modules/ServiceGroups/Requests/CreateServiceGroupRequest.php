<?php

namespace App\Http\ApiV1\Modules\ServiceGroups\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class CreateServiceGroupRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string'],
            'sort' => ['nullable', 'integer'],
            'parent_id' => ['nullable', 'integer', Rule::exists('service_groups', 'id')],
            'name' => ['required', 'string'],
            'seller_id' => ['required', 'integer'],
        ];
    }
}
