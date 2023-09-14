<?php

namespace App\Http\ApiV1\Modules\Services\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;

class PatchServiceRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['string'],
            'base_price' => ['integer'],
            'service_group_id' => ['nullable', 'integer'],
            'name' => ['string'],
            'seller_id' => ['integer'],
        ];
    }
}
