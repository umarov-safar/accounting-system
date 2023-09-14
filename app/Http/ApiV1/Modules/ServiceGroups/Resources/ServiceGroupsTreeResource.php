<?php

namespace App\Http\ApiV1\Modules\ServiceGroups\Resources;

use App\Domain\Services\Models\ServiceGroup;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

/**
 * @mixin ServiceGroup
 */
class ServiceGroupsTreeResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return $this->collection->keyBy('id')
            ->pipe(fn (Collection $source) => $this->buildTree($source))
            ->pipe(fn (Collection $source) => $this->mapServiceGroups($source))
            ->toArray();
    }

    private function serviceGroupToArray(ServiceGroup $serviceGroup): array
    {
        return [
            'id' => $serviceGroup->id,
            'name' => $serviceGroup->name,
            'seller_id' => $serviceGroup->seller_id,
            'description' => $serviceGroup->description,
            'parent_id' => $serviceGroup->parent_id,
            'children' => $this->when(
                $serviceGroup->relationLoaded('children'),
                fn () => $this->mapServiceGroups($serviceGroup->children),
                []
            ),
        ];
    }

    private function mapServiceGroups(Collection $serviceGroups): Collection
    {
        return $serviceGroups->map(
            fn (ServiceGroup $serviceGroup) => $this->serviceGroupToArray($serviceGroup)
        );
    }

    private function buildTree(Collection $source): Collection
    {
        $result = new Collection();

        /** @var ServiceGroup $serviceGroup */
        foreach ($source as $serviceGroup) {
            if (!$serviceGroup->parent_id || !$source->has($serviceGroup->parent_id)) {
                $result->push($serviceGroup);

                continue;
            }

            $parent = $source->get($serviceGroup->parent_id);

            if (!$parent) {
                continue;
            }

            if (!$parent->relationLoaded('children')) {
                $parent->setRelation('children', new Collection());
            }

            $parent->children->push($serviceGroup);
        }

        return $result;
    }
}