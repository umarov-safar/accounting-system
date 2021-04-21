<?php

namespace App\Http\ApiV1\Support\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

abstract class BaseJsonResource extends JsonResource
{
    public static function collectionWithPagination($resource, array $pagination): AnonymousResourceCollection
    {
        $collection = static::collection($resource);
        $currentAdditional = $collection->additional ?: [];
        $append = ['meta' => ['pagination' => $pagination]];

        return static::collection($resource)->additional(array_merge_recursive($currentAdditional, $append));
    }
    
    public static function collectionFromStandardRequestQuery(Request $request, Builder|SpatieQueryBuilder $query): AnonymousResourceCollection
    {
        $skip = (int) $request->input('pagination.offset', 0);
        $limit = (int) $request->input('pagination.limit', config('pagination.default_limit'));

        $collection = $limit === 0 ? new Collection() : $query->skip($skip)->limit($limit)->get();

        $total = $limit > 0 && $collection->count() === $limit
            ? $query->cloneWithout(['limit', 'offset'])->count()
            : $skip + $collection->count();

        return static::collectionWithPagination($collection, [
            'offset' => $skip,
            'limit' => $limit,
            'total' => $total,
        ]);
    }

    protected function mapFileToResponse(string $fieldName) : ?array
    {
        $value = $this->$fieldName;
        if (!$value) {
            return null;
        }

        return [
            'path' => $value->path ?? '',
            'name' => $value->name ?? '',
            'url'  => $value->url ?? '',
        ];
    }
}
