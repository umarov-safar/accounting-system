<?php

namespace App\Domain\Support\Concerns\Tests\Stubs;

use App\Domain\Support\Concerns\AppliesToAggregate;
use App\Domain\Support\Models\Model;
use Closure;

class AppliesToAggregateAction
{
    use AppliesToAggregate;

    protected function createModel(): Model
    {
        return new AppliesToAggregateRoot();
    }

    public static function executeApply(AppliesToAggregateRoot $entity, Closure $action)
    {
        return (new static())->apply($entity->getKey(), $action);
    }

    public static function executeUpdateOrCreate(?AppliesToAggregateRoot $entity, Closure $action): AppliesToAggregateRoot
    {
        $entityId = $entity?->getKey();

        return (new static())->updateOrCreate($entityId, $action);
    }

    public static function executeDelete(AppliesToAggregateRoot $entity, ?Closure $action = null): void
    {
        (new static())->delete($entity->getKey(), $action);
    }
}
