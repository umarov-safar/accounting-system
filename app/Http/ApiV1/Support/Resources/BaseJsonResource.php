<?php

namespace App\Http\ApiV1\Support\Resources;

use App\Http\ApiV1\OpenApiGenerated\Dto\PaginationTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginationException;
use Illuminate\Pagination\CursorPaginator;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use UnexpectedValueException;

abstract class BaseJsonResource extends JsonResource
{
    public static function collectionWithPagination($resource, array $pagination): AnonymousResourceCollection
    {
        $collection = static::collection($resource);
        $currentAdditional = $collection->additional ?: [];
        $append = ['meta' => ['pagination' => $pagination]];

        return static::collection($resource)->additional(array_merge_recursive($currentAdditional, $append));
    }

    public static function collectionFromStandardRequestQuery(Request $request, Builder|SpatieQueryBuilder $query, bool $allowToBypassPagination = true, ?int $maxLimit = null): AnonymousResourceCollection
    {
        return $request->input('pagination.type', config('pagination.default_type')) === PaginationTypeEnum::CURSOR
            ? static::collectionFromStandardRequestQueryWithCursorPagination($request, $query, $allowToBypassPagination, $maxLimit)
            : static::collectionFromStandardRequestQueryWithOffsetPagination($request, $query, $allowToBypassPagination, $maxLimit);
    }

    /**
     * @throws BadRequestHttpException if
     *  - invalid pagination cursor is passed
     *  - different order by directions are used for cursor pagination
     */
    public static function collectionFromStandardRequestQueryWithCursorPagination(Request $request, Builder|SpatieQueryBuilder $query, bool $allowToBypassPagination = true, ?int $maxLimit = null): AnonymousResourceCollection
    {
        $limit = (int) $request->input('pagination.limit', config('pagination.default_limit'));
        $limit = $maxLimit !== null && $maxLimit > 0 ? min($limit, $maxLimit) : $limit;

        if ($limit <= 0) {
            $collection = $limit < 0 && $allowToBypassPagination ? $query->get() : new Collection();

            return static::collectionWithPagination($collection, [
                'cursor' => null,
                'limit' => $limit,
                'next_cursor' => null,
                'previous_cursor' => null,
                'type' => PaginationTypeEnum::CURSOR,
            ]);
        }

        $cursorHash = $request->input('pagination.cursor', null);
        $cursorHash = $cursorHash === '' ? null : $cursorHash;

        $cursor = Cursor::fromEncoded($cursorHash);
        if ($cursorHash !== null && $cursor === null) {
            throw new BadRequestHttpException("Unable to decode pagination cursor");
        }

        try {
            /** @var CursorPaginator */
            $paginator = $query->cursorPaginate($limit, cursor: $cursor);
        } catch (CursorPaginationException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (UnexpectedValueException $e) {
            throw new BadRequestHttpException("Invalid pagination cursor: {$e->getMessage()}");
        }

        return static::collectionWithPagination($paginator->items(), [
            'cursor' => $cursorHash,
            'limit' => $limit,
            'next_cursor' => $paginator->nextCursor()?->encode(),
            'previous_cursor' => $paginator->previousCursor()?->encode(),
            'type' => PaginationTypeEnum::CURSOR,
        ]);
    }

    public static function collectionFromStandardRequestQueryWithOffsetPagination(Request $request, Builder|SpatieQueryBuilder $query, bool $allowToBypassPagination = true, ?int $maxLimit = null): AnonymousResourceCollection
    {
        $skip = (int) $request->input('pagination.offset', 0);
        $limit = (int) $request->input('pagination.limit', config('pagination.default_limit'));
        $limit = $maxLimit !== null && $maxLimit > 0 ? min($limit, $maxLimit) : $limit;

        if ($limit <= 0) {
            $collection = $limit < 0 && $allowToBypassPagination ? $query->get() : new Collection();

            return static::collectionWithPagination($collection, [
                'offset' => $skip,
                'limit' => $limit,
                'total' => $collection->count(),
                'type' => PaginationTypeEnum::OFFSET,
            ]);
        }

        $queryClone = $query->clone();
        $collection = $query->skip($skip)->limit($limit)->get();

        $total = $collection->count() === $limit
            ? $queryClone->count()
            : $skip + $collection->count();

        return static::collectionWithPagination($collection, [
            'offset' => $skip,
            'limit' => $limit,
            'total' => $total,
            'type' => PaginationTypeEnum::OFFSET,
        ]);
    }
}
