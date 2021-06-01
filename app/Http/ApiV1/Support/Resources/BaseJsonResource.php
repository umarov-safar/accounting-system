<?php

namespace App\Http\ApiV1\Support\Resources;

use App\Http\ApiV1\Support\Pagination\Page;
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

    public static function collectPage(Page $page): AnonymousResourceCollection
    {
        return static::collectionWithPagination($page->items, $page->pagination);
    }
}
