<?php

namespace App\Http\ApiV1\Modules\ServiceGroups\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class PatchServiceGroupRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['sometimes', 'nullable', 'string'],
            'sort' => ['sometimes','nullable', 'integer'],
            'parent_id' => ['sometimes', 'nullable', 'integer', Rule::exists('service_groups', 'id')],
            'name' => ['sometimes','string'],
            'seller_id' => ['sometimes','integer'],
        ];
    }
}
