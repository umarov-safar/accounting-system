<?php

namespace App\Http\ApiV1\Modules\Nomenclatures\Requests;

use App\Http\ApiV1\OpenApiGenerated\Enums\ObjectTypeEnum;
use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class PatchNomenclatureRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'base_price' => ['sometimes', 'integer'],
            'obj_type' => ['sometimes', 'string', Rule::enum(ObjectTypeEnum::class)],
            'obj_id' => ['sometimes', 'integer'],
            'is_service' => ['sometimes', 'boolean'],
            'seller_id' => ['sometimes', 'integer',
                Rule::unique('nomenclatures')->where(function (Builder $query) {
                    return $query->where('obj_type', $this->obj_type)
                        ->where('obj_id', $this->obj_id)
                        ->where('seller_id', $this->seller_id);
                })->ignore($this->route('id'))
            ],
        ];
    }

    public function messages()
    {
        return [
            'seller_id.unique' => 'Такое значение поля [seller_id, obj_id, obj_type] уже существует.',
        ];
    }
}
