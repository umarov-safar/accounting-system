<?php

namespace App\Http\ApiV1\Support\Pagination;

use App\Http\ApiV1\OpenApiGenerated\Enums\PaginationTypeEnum;
use Illuminate\Database\Eloquent\Collection;

class OffsetPageBuilder extends AbstractPageBuilder
{
    public function build(): Page
    {
        $limit = $this->applyMaxLimit((int) $this->request->input('pagination.limit', $this->getDefaultLimit()));

        return $limit > 0
            ? $this->buildWithPositiveLimit($limit)
            : $this->buildWithNotPositiveLimit($limit);
    }

    protected function buildWithNotPositiveLimit(int $limit): Page
    {
        $collection = $limit < 0 && !$this->forbidToBypassPagination ? $this->query->get() : new Collection();

        return new Page($collection, [
            'offset' => 0,
            'limit' => $limit,
            'total' => $collection->count(),
            'type' => PaginationTypeEnum::OFFSET->value,
        ]);
    }

    protected function buildWithPositiveLimit(int $limit): Page
    {
        $skip = (int) $this->request->input('pagination.offset', 0);

        $this->queryClone = $this->query->clone();
        $collection = $this->query->skip($skip)->limit($limit)->get();

        $total = $collection->count() === $limit
            ? $this->queryClone->count()
            : $skip + $collection->count();

        return new Page($collection, [
            'offset' => $skip,
            'limit' => $limit,
            'total' => $total,
            'type' => PaginationTypeEnum::OFFSET->value,
        ]);
    }
}
