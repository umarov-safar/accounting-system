<?php

namespace App\Http\ApiV1\Modules\Nomenclatures\Resources;

use App\Domain\Nomenclatures\Models\Nomenclature;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin Nomenclature
 */
class NomenclaturesResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'seller_id' => $this->seller_id,
            'is_service' => $this->is_service,
            'obj_id' => $this->obj_id,
            'obj_type' => $this->obj_type,
            'base_price' => $this->base_price,
	        'is_new' => $this->is_new,
            'cardonor_id' => $this->cardonor_id,
            'quantity_fix' => $this->quantity_fix,
            'quantity_no_fix' => $this->quantity_no_fix,
            'parent_id' => $this->parent_id,
            'zippy_nomenclature_id' => $this->zippy_nomenclature_id,
        ];
    }
}
