<?php

namespace App\Http\ApiV1\Modules\Nomenclatures\Resources;

use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin todo
 */
class NomenclaturesResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        // todo
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
            'cardonor_id' => $this->cardonor_id
        ];
    }
}
