<?php

namespace App\Http\ApiV1\Modules\Documents\Resources;

use App\Domain\Documents\Models\DocumentType;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin DocumentType
 */
class DocumentTypesResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'target' => $this->target,
            'title' => $this->title,
        ];
    }
}