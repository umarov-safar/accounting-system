<?php

namespace App\Http\ApiV1\Support\Requests;

class MassDeleteRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ];
    }

    /**
     * @return array|int[]
     */
    public function getIds(): array
    {
        return $this->validated()['ids'];
    }
}
