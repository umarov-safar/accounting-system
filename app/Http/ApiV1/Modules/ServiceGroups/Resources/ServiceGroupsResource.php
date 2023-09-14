<?php

namespace App\Http\ApiV1\Modules\ServiceGroups\Resources;

use App\Domain\Services\Models\ServiceGroup;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin ServiceGroup
 */
class ServiceGroupsResource extends BaseJsonResource
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
            'parent_id' => $this->parent_id,
            'sort' => $this->sort,
            'description' => $this->description,
            'children' => ServiceGroupsResource::collection($this->whenLoaded('children')),
        ];
    }
}