<?php

namespace App\Domain\Support\Concerns\Tests\Stubs;

use App\Domain\Support\Concerns\AppliesToDrivenEntity;
use App\Domain\Support\Models\Model;
use Closure;

class AppliesToDrivenEntityAction
{
    use AppliesToDrivenEntity;

    protected function createRootModel(): Model
    {
        return new AppliesToDrivenEntityRoot();
    }

    protected function createModel(): Model
    {
        return new AppliesToDrivenEntityEntity();
    }

    protected function getRelationNameInRoot(): string
    {
        return 'children';
    }

    public static function executeCreate(AppliesToDrivenEntityRoot $root, Closure $action): AppliesToDrivenEntityEntity
    {
        return (new static())->updateOrCreate(null, $root->id, $action);
    }

    public static function executeUpdate(AppliesToDrivenEntityEntity $entity, Closure $action): AppliesToDrivenEntityEntity
    {
        return (new static())->updateOrCreate($entity->id, null, $action);
    }

    public static function executeDelete(AppliesToDrivenEntityEntity $entity, ?Closure $action = null): void
    {
        (new static())->delete($entity->id, $action);
    }

    public static function executeApply(AppliesToDrivenEntityEntity $entity, Closure $action): mixed
    {
        return (new static())->apply($entity->id, $action);
    }
}
