<?php

namespace App\Http\ApiV1\Modules\Services\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;

class ReplaceServiceRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['string'],
            'base_price' => ['integer'],
            'service_group_id' => ['required', 'nullable', 'integer'],
            'name' => ['required', 'string'],
            'seller_id' => ['required', 'integer'],
        ];
    }
}
