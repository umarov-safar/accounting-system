<?php

namespace App\Http\ApiV1\Support\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseJsonResource extends JsonResource
{
    public static function collectionWithPagination($resource, array $pagination): AnonymousResourceCollection
    {
        $collection = static::collection($resource);
        $currentAdditional = $collection->additional ?: [];
        $append = ['meta' => ['pagination' => $pagination]];

        return static::collection($resource)->additional(array_merge_recursive($currentAdditional, $append));
    }
}
