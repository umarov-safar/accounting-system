<?php

namespace App\Http\ApiV1\Modules\Services\Resources;

use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin todo
 */
class ServicesResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        // todo
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'seller_id' => $this->seller_id,
            'name' => $this->name,
            'service_group_id' => $this->service_group_id,
            'base_price' => $this->base_price,
            'description' => $this->description,
            'service_group' => $this->service_group,
        ];
    }
}