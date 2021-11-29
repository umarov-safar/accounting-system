<?php

namespace App\Http\ApiV1\Support\Resources;

use App\Http\ApiV1\Support\Pagination\Page;
use DateTime;
use Ensi\LaravelEnsiFilesystem\Models\EnsiFile;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseJsonResource extends JsonResource
{
    public const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.u\Z';
    public const DATE_FORMAT = 'Y-m-d';

    public function dateTimeToIso(?DateTime $datetime): ?string
    {
        return $datetime ? $datetime->format(static::DATE_TIME_FORMAT) : null;
    }

    public function dateToIso(?DateTime $date): ?string
    {
        return $date ? $date->format(static::DATE_FORMAT) : null;
    }

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

    protected function mapPublicFileToResponse(?string $filePath): ?EnsiFile
    {
        return $filePath ? EnsiFile::public($filePath) : null;
    }

    protected function mapProtectedFileToResponse(?string $filePath): ?EnsiFile
    {
        return $filePath ? EnsiFile::protected($filePath) : null;
    }
}
